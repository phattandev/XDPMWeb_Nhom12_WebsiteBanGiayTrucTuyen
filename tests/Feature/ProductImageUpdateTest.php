<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Shoe;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class ProductImageUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('filesystems.disks.cloudinary', [
            'driver' => 'cloudinary',
            'url' => 'cloudinary://test-key:test-secret@test-cloud',
            'secure' => true,
        ]);
    }

    public function test_it_replaces_old_images_only_after_new_upload_succeeds(): void
    {
        $user = $this->createUser();
        $category = Category::create(['name' => 'Sneaker']);
        $brand = Brand::create(['name' => 'Nike']);
        $shoe = Shoe::create([
            'name' => 'Old Name',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 1000000,
            'description' => 'Old description',
        ]);

        DB::table('shoe_images')->insert([
            [
                'shoe_id' => $shoe->id,
                'image_url' => 'https://res.cloudinary.com/demo/image/upload/old-1.jpg',
                'public_id' => 'old-1',
                'is_primary' => true,
            ],
            [
                'shoe_id' => $shoe->id,
                'image_url' => 'https://res.cloudinary.com/demo/image/upload/old-2.jpg',
                'public_id' => 'old-2',
                'is_primary' => false,
            ],
        ]);

        $uploadApi = Mockery::mock();

        $uploadApi->shouldReceive('upload')
            ->twice()
            ->andReturn(
                $this->fakeCloudinaryUpload('https://res.cloudinary.com/demo/image/upload/new-1.jpg', 'new-1'),
                $this->fakeCloudinaryUpload('https://res.cloudinary.com/demo/image/upload/new-2.jpg', 'new-2'),
            );

        $uploadApi->shouldReceive('destroy')
            ->once()
            ->with('old-1')
            ->andReturnTrue();

        $uploadApi->shouldReceive('destroy')
            ->once()
            ->with('old-2')
            ->andReturnTrue();

        Cloudinary::swap($this->fakeCloudinaryClient($uploadApi));

        $response = $this->actingAs($user)->from(route('admin.products.edit', $shoe->id))->post(
            route('admin.products.update', $shoe->id),
            [
                '_method' => 'PUT',
                'name' => 'Updated Name',
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'price' => 1500000,
                'description' => 'Updated description',
                'images' => [
                    $this->fakePngUpload('new-1.png'),
                    $this->fakePngUpload('new-2.png'),
                ],
            ]
        );

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('shoe_images', [
            'shoe_id' => $shoe->id,
            'public_id' => 'old-1',
        ]);

        $this->assertDatabaseMissing('shoe_images', [
            'shoe_id' => $shoe->id,
            'public_id' => 'old-2',
        ]);

        $this->assertDatabaseHas('shoe_images', [
            'shoe_id' => $shoe->id,
            'public_id' => 'new-1',
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('shoe_images', [
            'shoe_id' => $shoe->id,
            'public_id' => 'new-2',
            'is_primary' => false,
        ]);
    }

    public function test_it_rolls_back_product_changes_when_image_upload_fails(): void
    {
        $user = $this->createUser();
        $category = Category::create(['name' => 'Sneaker']);
        $brand = Brand::create(['name' => 'Nike']);
        $shoe = Shoe::create([
            'name' => 'Original Name',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 1000000,
            'description' => 'Original description',
        ]);

        DB::table('shoe_images')->insert([
            'shoe_id' => $shoe->id,
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/original.jpg',
            'public_id' => 'original-image',
            'is_primary' => true,
        ]);

        $uploadApi = Mockery::mock();

        $uploadApi->shouldReceive('upload')
            ->once()
            ->andThrow(new \RuntimeException('Cloudinary upload failed'));

        $uploadApi->shouldReceive('destroy')->never();

        Cloudinary::swap($this->fakeCloudinaryClient($uploadApi));

        $response = $this->actingAs($user)->from(route('admin.products.edit', $shoe->id))->post(
            route('admin.products.update', $shoe->id),
            [
                '_method' => 'PUT',
                'name' => 'Broken Update',
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'price' => 2000000,
                'description' => 'This should not persist',
                'images' => [
                    $this->fakePngUpload('broken.png'),
                ],
            ]
        );

        $response->assertRedirect(route('admin.products.edit', $shoe->id));
        $response->assertSessionHas('error');

        $shoe->refresh();

        $this->assertSame('Original Name', $shoe->name);
        $this->assertSame('Original description', $shoe->description);
        $this->assertSame(1000000.0, (float) $shoe->price);

        $this->assertDatabaseHas('shoe_images', [
            'shoe_id' => $shoe->id,
            'public_id' => 'original-image',
        ]);
    }

    private function createUser(): User
    {
        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }

    private function fakeCloudinaryUpload(string $securePath, string $publicId): array
    {
        return [
            'secure_url' => $securePath,
            'public_id' => $publicId,
        ];
    }

    private function fakePngUpload(string $name): UploadedFile
    {
        return UploadedFile::fake()->image($name);
    }

    private function fakeCloudinaryClient(object $uploadApi): object
    {
        return new class($uploadApi) {
            public function __construct(
                private readonly object $uploadApi,
            ) {
            }

            public function uploadApi(): object
            {
                return $this->uploadApi;
            }
        };
    }
}

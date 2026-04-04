<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Tests\TestCase;

class ProductImageCreateTest extends TestCase
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

    public function test_it_creates_a_product_and_persists_cloudinary_images(): void
    {
        $user = $this->createAdminUser();
        $category = Category::create(['name' => 'Running']);
        $brand = Brand::create(['name' => 'Adidas']);

        $uploadApi = Mockery::mock();

        $uploadApi->shouldReceive('upload')
            ->once()
            ->withArgs(fn ($path, $options) => is_string($path) && ($options['folder'] ?? null) === 'shoes_store')
            ->andReturn($this->fakeCloudinaryUpload('https://res.cloudinary.com/demo/image/upload/create-1.jpg', 'create-1'));

        $uploadApi->shouldReceive('upload')
            ->once()
            ->withArgs(fn ($path, $options) => is_string($path) && ($options['folder'] ?? null) === 'shoes_store')
            ->andReturn($this->fakeCloudinaryUpload('https://res.cloudinary.com/demo/image/upload/create-2.jpg', 'create-2'));

        $uploadApi->shouldReceive('destroy')->never();

        Cloudinary::swap($this->fakeCloudinaryClient($uploadApi));

        $response = $this->actingAs($user)->post(route('admin.products.store'), [
            'name' => 'Adidas Adizero',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 3200000,
            'description' => 'Lightweight race shoe',
            'images' => [
                $this->fakePngUpload('create-1.png'),
                $this->fakePngUpload('create-2.png'),
            ],
            'variants' => [
                [
                    'color' => 'Blue',
                    'size' => 42,
                    'stock_quantity' => 8,
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('shoes', [
            'name' => 'Adidas Adizero',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 3200000,
        ]);

        $shoeId = (int) \App\Models\Shoe::where('name', 'Adidas Adizero')->value('id');

        $this->assertDatabaseHas('shoe_images', [
            'shoe_id' => $shoeId,
            'public_id' => 'create-1',
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/create-1.jpg',
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('shoe_images', [
            'shoe_id' => $shoeId,
            'public_id' => 'create-2',
            'image_url' => 'https://res.cloudinary.com/demo/image/upload/create-2.jpg',
            'is_primary' => false,
        ]);

        $this->assertDatabaseHas('shoe_variants', [
            'shoe_id' => $shoeId,
            'color' => 'Blue',
            'size' => 42,
            'stock_quantity' => 8,
        ]);
    }

    public function test_it_rolls_back_product_creation_when_image_upload_fails(): void
    {
        $user = $this->createAdminUser();
        $category = Category::create(['name' => 'Running']);
        $brand = Brand::create(['name' => 'Adidas']);

        $uploadApi = Mockery::mock();

        $uploadApi->shouldReceive('upload')
            ->once()
            ->andThrow(new \RuntimeException('Cloudinary upload failed during create'));

        $uploadApi->shouldReceive('destroy')->never();

        Cloudinary::swap($this->fakeCloudinaryClient($uploadApi));

        $response = $this->actingAs($user)->from(route('admin.products.create'))->post(route('admin.products.store'), [
            'name' => 'Broken Product',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 2500000,
            'description' => 'Should be rolled back',
            'images' => [
                $this->fakePngUpload('broken-create.png'),
            ],
        ]);

        $response->assertRedirect(route('admin.products.create'));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('shoes', [
            'name' => 'Broken Product',
        ]);

        $this->assertDatabaseCount('shoe_images', 0);
    }

    private function createAdminUser(): User
    {
        return User::create([
            'name' => 'Admin User',
            'email' => 'admin-create@example.com',
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

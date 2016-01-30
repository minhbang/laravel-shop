<?php
//use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\DatabaseTransactions;
use Minhbang\User\User;

class OrderManageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Truy cập trang quản lý đơn hàng
     */
    public function testAccessOrderManagementPage()
    {
        // Yêu cầu đăng nhập
        $this->visit('/backend/order')
            ->seePageIs('/auth/login');

        // Không có quyền truy cập
        $user = factory(User::class)->create();
        $this->actingAs($user)->get('/backend/order')
            ->assertResponseStatus(403);

        // Truy cập thành công
        $admin = factory(User::class, 'admin')->create();
        $this->actingAs($admin)->get('/backend/order')
            ->assertResponseOk();

        // Truy cập bằng quyền Super Admin
        $super_admin = factory(User::class, 'super_admin')->create();
        $this->actingAs($super_admin)->visit('/backend/order')
            ->see(trans('shop::order.manage_title'));
    }
}
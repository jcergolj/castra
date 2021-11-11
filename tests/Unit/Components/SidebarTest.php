<?php

namespace Tests\Unit\Components;

use App\Services\AdminNav;
use App\Services\MemberNav;
use App\View\Components\Sidebar;
use Exception;
use Tests\TestCase;

/** @see \App\Components\Sidebar */
class SidebarTest extends TestCase
{
    /** @test */
    public function member_nav_is_returned_from_strategy()
    {
        $component = new Sidebar(create_member());
        $this->assertInstanceOf(MemberNav::class, $component->menuStrategy());
    }

    /** @test */
    public function admin_nav_is_returned_from_strategy()
    {
        $component = new Sidebar(create_admin());
        $this->assertInstanceOf(AdminNav::class, $component->menuStrategy());
    }

    /** @test */
    public function member_nav_is_returned_from_strategy_for_auth_user()
    {
        $this->makeRequestWithAuth(create_user());

        $component = $this->app->make(Sidebar::class);
        $this->assertInstanceOf(MemberNav::class, $component->menuStrategy());
    }

    /** @test */
    public function assert_menu_strategy_method_is_called()
    {
        $component = $this->getMockBuilder(Sidebar::class)
            ->setConstructorArgs(['user' => create_member()])
            ->setMethods(['menuStrategy'])
            ->getMock();

        $component->expects($this->once())
            ->method('menuStrategy')
            ->willReturn(new MemberNav);

        $component->render();
    }

    /** @test */
    public function component_is_rendered()
    {
        $this->component(Sidebar::class, ['user' => create_member()])
            ->assertSee(route('dashboards.index'))
            ->assertSeeText('Dashboard');
    }

    /** @test */
    public function assert_exception_is_thrown()
    {
        $this->makeRequestWithAuth(create_user(['role' => 'undefined']));

        $this->expectException(Exception::class);
        $component = $this->app->make(Sidebar::class);

        $component->menuStrategy();
    }
}

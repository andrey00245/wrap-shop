<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewUsers;
use App\Nova\Metrics\OrdersPerDay;
use App\Nova\Metrics\SalesByStatus;
use App\Nova\Metrics\TotalSales;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
	/**
	 * Get the cards for the dashboard.
	 *
	 * @return array
	 */
	public function cards()
	{
		return [
			new TotalSales,
			new NewUsers,
			new OrdersPerDay,
			new SalesByStatus,
			new Help,
		];
	}
}

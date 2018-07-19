<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

abstract class ApiController extends Controller
{
    use Helpers;

	public function __construct()
	{
		$this->middleware('sentry');
	}
}

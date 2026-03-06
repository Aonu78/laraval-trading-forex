<?php

namespace Tests\Unit;

use App\Http\Controllers\CryptoTradeController;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class CryptoTradeControllerSettlementTest extends TestCase
{
    public function test_buy_wins_when_price_goes_up()
    {
        $controller = new CryptoTradeController();
        $method = new ReflectionMethod($controller, 'determineMarketResult');
        $method->setAccessible(true);

        $result = $method->invoke($controller, 'buy', 100.0, 120.0);

        $this->assertTrue($result);
    }

    public function test_sell_wins_when_price_goes_down()
    {
        $controller = new CryptoTradeController();
        $method = new ReflectionMethod($controller, 'determineMarketResult');
        $method->setAccessible(true);

        $result = $method->invoke($controller, 'sell', 120.0, 100.0);

        $this->assertTrue($result);
    }

    public function test_equal_price_returns_neutral_result()
    {
        $controller = new CryptoTradeController();
        $method = new ReflectionMethod($controller, 'determineMarketResult');
        $method->setAccessible(true);

        $result = $method->invoke($controller, 'buy', 100.0, 100.0);

        $this->assertNull($result);
    }

    public function test_bias_100_percent_keeps_market_result()
    {
        $controller = new CryptoTradeController();
        $method = new ReflectionMethod($controller, 'applyBiasResult');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($controller, true, 100));
        $this->assertFalse($method->invoke($controller, false, 100));
    }

    public function test_bias_0_percent_flips_market_result()
    {
        $controller = new CryptoTradeController();
        $method = new ReflectionMethod($controller, 'applyBiasResult');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($controller, true, 0));
        $this->assertTrue($method->invoke($controller, false, 0));
    }
}

<?php

namespace App\Services;

class WayForPayService
{
    protected $merchant;
    protected $secret;

    public function __construct()
    {
        $this->merchant = env('WAYFORPAY_MERCHANT');
        $this->secret = env('WAYFORPAY_SECRET');
    }

    protected function generateSignature(array $data): string
    {
        $signatureParts = [
            $data['merchantAccount'],
            $data['merchantDomainName'],
            $data['orderReference'],
            $data['orderDate'],
            $data['amount'],
            $data['currency'],
        ];

        foreach ($data['productName'] as $name) {
            $signatureParts[] = $name;
        }
        foreach ($data['productCount'] as $count) {
            $signatureParts[] = $count;
        }
        foreach ($data['productPrice'] as $price) {
            $signatureParts[] = $price;
        }

        $signatureString = implode(';', $signatureParts);

        return hash_hmac('md5', $signatureString, $this->secret);
    }


    public function generatePaymentData($order): array
    {
        $productNames = [];
        $productCounts = [];
        $productPrices = [];

        $totalAmount = 0;
        foreach ($order->products as $product) {
            $productNames[] = $product->name;
            $productCounts[] = (int) $product->pivot->quantity;
            $priceFormatted = number_format($product->pivot->price, 2, '.', '');
            $productPrices[] = $priceFormatted;
            $totalAmount += $product->pivot->quantity * $product->pivot->price;
        }
        $totalAmount = number_format($totalAmount, 2, '.', '');

        $reference = $order->id . '_' . time();
        $date = time();

        $data = [
            'merchantAccount' => $this->merchant,
            'merchantDomainName' => request()->getHost(),
            'orderReference' => $reference,
            'orderDate' => $date,
            'amount' => $totalAmount,
            'currency' => 'UAH',
            'productName' => $productNames,
            'productCount' => $productCounts,
            'productPrice' => $productPrices,
            'clientFirstName' => $order->first_name,
            'clientLastName' => $order->last_name,
            'clientEmail' => $order->email,
            'clientPhone' => $order->phone,
            'language' => 'UA',
            'returnUrl' => route('wayforpay.success'),
            'serviceUrl' => route('wayforpay.callback'),
        ];

        $data['merchantSignature'] = $this->generateSignature($data);

        return $data;
    }
}

<?php

it('returns not found when invoice does not exist', function (): void {
    $response = $this->get('/payment/test-invoice');

    $response->assertNotFound();
});

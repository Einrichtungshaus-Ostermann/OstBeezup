{extends file="parent:frontend/checkout/finish.tpl"}

{block name="frontend_index_body_inline"}
    {$smarty.block.parent}
    <img src="https://tracker.beezup.com/SO?StoreId={$beezup.storeID}&OrderMerchantId={$sOrderNumber}&TotalCost={$sBasket.AmountNet|replace:",":"."}&ValidPayement=TRUE&ListProductId={$beezup.ListProductId}&ListProductQuantity={$beezup.ListProductQuantity}&ListProductUnitPrice={$beezup.ListProductUnitPrice}&ListProductMargin={$beezup.ListProductMargin}"/>
{/block}



{* file to extend *}
{extends file="parent:frontend/checkout/finish.tpl"}

{* set namespace *}
{namespace name="frontend/ost-beezup/checkout/finish"}



{* insert image to body inline *}
{block name="frontend_index_body_inline"}
    {$smarty.block.parent}
    <img src="https://tracker.beezup.com/SO?StoreId={$ostBeezup.storeId}&OrderMerchantId={$sOrderNumber}&TotalCost={$sBasket.AmountNet|replace:",":"."}&ValidPayement=TRUE&ListProductId={$ostBeezup.ListProductId}&ListProductQuantity={$ostBeezup.ListProductQuantity}&ListProductUnitPrice={$ostBeezup.ListProductUnitPrice}&ListProductMargin={$ostBeezup.ListProductMargin}"/>
{/block}

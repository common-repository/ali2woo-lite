<?php

use AliNext_Lite\Aliexpress;
use AliNext_Lite\AliexpressHelper;
use AliNext_Lite\ApplyPricingRulesProcess;
use AliNext_Lite\Attachment;
use AliNext_Lite\BackgroundProcessFactory;
use AliNext_Lite\BackgroundProcessService;
use AliNext_Lite\ExternalOrderFactory;
use AliNext_Lite\FulfillmentClient;
use AliNext_Lite\Helper;
use AliNext_Lite\ImportAjaxController;
use AliNext_Lite\ImportListService;
use AliNext_Lite\ImportProcess;
use AliNext_Lite\OrderFulfillmentService;
use AliNext_Lite\Override;
use AliNext_Lite\PermanentAlertService;
use AliNext_Lite\PriceFormulaFactory;
use AliNext_Lite\PriceFormulaRepository;
use AliNext_Lite\PriceFormulaService;
use AliNext_Lite\PriceFormulaSetAjaxController;
use AliNext_Lite\PriceFormulaSetFactory;
use AliNext_Lite\PriceFormulaSetRepository;
use AliNext_Lite\PriceFormulaSetService;
use AliNext_Lite\PriceFormulaSettingsRepository;
use AliNext_Lite\ProductChange;
use AliNext_Lite\ProductImport;
use AliNext_Lite\Review;
use AliNext_Lite\Woocommerce;
use function DI\create;
use function DI\get;

return [
    /* helpers */
    'AliNext_Lite\AliexpressHelper' => create(AliexpressHelper::class),

    /* apis */
    'AliNext_Lite\FulfillmentClient' => create(FulfillmentClient::class),

    /* factories */
    'AliNext_Lite\BackgroundProcessFactory' => create(BackgroundProcessFactory::class),
    'AliNext_Lite\ExternalOrderFactory' => create(ExternalOrderFactory::class),
    'AliNext_Lite\PriceFormulaFactory' => create(PriceFormulaFactory::class),
    'AliNext_Lite\PriceFormulaSetFactory' => create(PriceFormulaSetFactory::class)
        ->constructor(
            get(PriceFormulaFactory::class),
        ),

    /* repository */
    'AliNext_Lite\PriceFormulaRepository' => create(PriceFormulaRepository::class)
        ->constructor(
            get(PriceFormulaFactory::class)
        ),
    'AliNext_Lite\PriceFormulaSetRepository' => create(PriceFormulaSetRepository::class)
        ->constructor(
            get(PriceFormulaSetFactory::class)
        ),

    /* models */
    'AliNext_Lite\Attachment' => create(Attachment::class),
    'AliNext_Lite\Helper' => create(Helper::class),
    'AliNext_Lite\ProductChange' => create(ProductChange::class),
    'AliNext_Lite\ProductImport' => create(ProductImport::class),
    'AliNext_Lite\Woocommerce' => create(Woocommerce::class)
        ->constructor(
            get(Attachment::class), get(Helper::class), get(ProductChange::class)
        ),
    'AliNext_Lite\Review' => create(Review::class),
    'AliNext_Lite\Override' => create(Override::class),
    'AliNext_Lite\Aliexpress' => create(Aliexpress::class),

    /* services */
    'AliNext_Lite\BackgroundProcessService' => create(BackgroundProcessService::class)
        ->constructor(get(ApplyPricingRulesProcess::class), get(ImportProcess::class)),
    'AliNext_Lite\PermanentAlertService' => create(PermanentAlertService::class)
        ->constructor(get(BackgroundProcessService::class)),
    'AliNext_Lite\ImportListService' => create(ImportListService::class)
        ->constructor(get(ProductImport::class), get(Aliexpress::class)),
    'AliNext_Lite\OrderFulfillmentService' => create(OrderFulfillmentService::class)
        ->constructor(get(Aliexpress::class), get(ExternalOrderFactory::class)),
    'AliNext_Lite\PriceFormulaService' => create(PriceFormulaService::class)
        ->constructor(
            get(PriceFormulaRepository::class),
            get(PriceFormulaSettingsRepository::class)
        ),
    'AliNext_Lite\PriceFormulaSetService' => create(PriceFormulaSetService::class)
        ->constructor(
            get(PriceFormulaRepository::class),
            get(PriceFormulaSettingsRepository::class),
            get(PriceFormulaSetFactory::class),
            get(BackgroundProcessFactory::class)
        ),

    /* controllers */
    'AliNext_Lite\ImportAjaxController' => create(ImportAjaxController::class)
        ->constructor(
            get(ProductImport::class), get(Woocommerce::class), get(Review::class),
            get(Override::class), get(Aliexpress::class)
        ),

    'AliNext_Lite\PriceFormulaSetAjaxController' => create(PriceFormulaSetAjaxController::class)
        ->constructor(
            get(PriceFormulaSetRepository::class),
            get(PriceFormulaSetService::class)
        ),
];

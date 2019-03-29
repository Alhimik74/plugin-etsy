<?php

namespace Etsy;

use Etsy\Contracts\CategoryRepositoryContract;
use Etsy\Contracts\LegalInformationRepositoryContract;
use Etsy\Contracts\PropertyRepositoryContract;
use Etsy\Repositories\CategoryRepository;
use Etsy\Repositories\LegalInformationRepository;
use Etsy\Repositories\PropertyRepository;
use Etsy\Repositories\TaxonomyRepository;
use Plenty\Log\Services\ReferenceContainer;
use Plenty\Modules\Cron\Services\CronContainer;
use Plenty\Modules\EventProcedures\Services\Entries\ProcedureEntry;
use Plenty\Modules\EventProcedures\Services\EventProceduresService;
use Plenty\Plugin\ServiceProvider;

use Etsy\Crons\ItemExportCron;
use Etsy\Crons\StockUpdateCron;
use Etsy\Crons\OrderImportCron;
use Etsy\Factories\ItemDataProviderFactory;
use Etsy\DataProviders\ItemExportDataProvider;
use Etsy\DataProviders\ItemUpdateDataProvider;
use Etsy\Contracts\TaxonomyRepositoryContract;

/**
 * Class EtsyServiceProvider
 */
class EtsyServiceProvider extends ServiceProvider
{
    const PLUGIN_NAME = 'Etsy';

    const START_LISTING_SERVICE = 'StartListingService';
    const START_LISTING_INVENTORY = 'AddInventory';
    const UPDATE_LISTING_SERVICE = 'UpdateListingService';
    const UPDATE_LISTING_INVENTORY = 'UpdateInventory';
    const DELETE_LISTING_SERVICE = 'DeleteListingService';
    const ADD_LISTING_TRANSLATIONS = 'AddListingTranslations';

    const UPLOAD_LISTING_IMAGE = 'UpdateListingImages';
    const DELETE_LISTING_IMAGE = 'DeleteListingImages';

    const ITEM_EXPORT_SERVICE = 'ItemExportService';
    const STOCK_UPDATE_SERVICE = 'ItemUpdateStockService';

    const LISTING_UPDATE_STOCK_SERVICE = 'UpdateListingStockService';

    const ITEM_EXPORT_CRON = 'ItemExportCron';


    
	/**
	 * @return void
	 */
	public function register()
	{
		$this->getApplication()->bind(TaxonomyRepositoryContract::class, TaxonomyRepository::class);
        $this->getApplication()->bind(CategoryRepositoryContract::class, CategoryRepository::class);
        $this->getApplication()->bind(PropertyRepositoryContract::class, PropertyRepository::class);
        $this->getApplication()->bind(LegalInformationRepositoryContract::class, LegalInformationRepository::class);

		$this->getApplication()->bind('Etsy\item.dataprovider.export', ItemExportDataProvider::class);
		$this->getApplication()->bind('Etsy\item.dataprovider.update', ItemUpdateDataProvider::class);

		$this->getApplication()->singleton(ItemDataProviderFactory::class);

		$this->getApplication()->register(EtsyRouteServiceProvider::class);

		$this->getApplication()->register(CatalogBootServiceProvider::class);
	}

	/**
	 * @param CronContainer          $container
	 * @param EventProceduresService $eventProceduresService
	 */
	public function boot(CronContainer $container, EventProceduresService $eventProceduresService, ReferenceContainer $referenceContainer)
	{
		$referenceContainer->add([
			                         	'etsyListingId'  		=> 'etsyListingId',
			                         	'etsyReceiptId'  		=> 'etsyReceiptId',
			                         	'etsyLanguage'   		=> 'etsyLanguage',
										'etsyExportListCount' 	=> 'etsyExportListCount'
		                         ]);

		// register crons
		$container->add(CronContainer::DAILY, ItemExportCron::class);
		$container->add(CronContainer::DAILY, StockUpdateCron::class);
		$container->add(CronContainer::HOURLY, OrderImportCron::class);

		// register event actions
		$eventProceduresService->registerProcedure('etsy', ProcedureEntry::PROCEDURE_GROUP_ORDER, [
			'de' => 'Versandbestätigung an Etsy senden',
			'en' => 'Send shipping notification to Etsy'
		], 'Etsy\\Procedures\\ShippingNotificationEventProcedure@run');

		$eventProceduresService->registerProcedure('etsy', ProcedureEntry::PROCEDURE_GROUP_ORDER, [
			'de' => 'Zahlungsbestätigung an Etsy senden',
			'en' => 'Send payment notification to Etsy'
		], 'Etsy\\Procedures\\PaymentNotificationEventProcedure@run');
	}
}

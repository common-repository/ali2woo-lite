<?php

/**
 * Description of PriceFormulaSetAjaxController
 *
 * @author Ali2Woo Team
 *
 * @autoload: a2wl_admin_init
 *
 * @ajax: true
 */
// phpcs:ignoreFile WordPress.Security.EscapeOutput.OutputNotEscaped
namespace AliNext_Lite;;

use Exception;

class PriceFormulaSetAjaxController extends AbstractController
{
    private PriceFormulaSetRepository $PriceFormulaSetRepository;
    private PriceFormulaSetService $PriceFormulaSetService;

    public function __construct(
        PriceFormulaSetRepository $PriceFormulaSetRepository,
        PriceFormulaSetService $PriceFormulaSetService
    ) {
        parent::__construct();

        $this->PriceFormulaSetRepository = $PriceFormulaSetRepository;
        $this->PriceFormulaSetService = $PriceFormulaSetService;

        add_action('wp_ajax_a2wl_save_set', [$this, 'ajaxSaveSet']);
        add_action('wp_ajax_a2wl_choose_set', [$this, 'ajaxChooseSet']);
        add_action('wp_ajax_a2wl_remove_set', [$this, 'ajaxRemoveSet']);
    }

    public function ajaxSaveSet(): void
    {
        $this->verifyNonceAjax();

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $setName = $_POST['name'];

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk();

        try {
            if ($this->PriceFormulaSetRepository->checkExists($setName)) {
                throw new RepositoryException(
                    _x( "A price formula set with that name already exists. Please choose a different name.",
                        'error text', 'ali2woo')
                );
            }
            $PriceFormulaSet = $this->PriceFormulaSetService->buildFromSettings($setName);
            $this->PriceFormulaSetRepository->add($PriceFormulaSet);

            restore_error_handler();
        } catch (RepositoryException $RepositoryException) {
            $result = ResultBuilder::buildError($RepositoryException->getMessage());
        } catch (Exception $Exception)  {
            a2wl_print_throwable($Exception);
            $result = ResultBuilder::buildError($Exception->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajaxChooseSet(): void
    {
        $this->verifyNonceAjax();

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $setName = $_POST['name'];

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk();

        try {
            if (!$this->PriceFormulaSetRepository->checkExists($setName)) {
                throw new RepositoryException(
                    _x("A price formula set with that name does`t exists. Please choose a different name.",
                        'error text', 'ali2woo')
                );
            }

            $PriceFormulaSet = $this->PriceFormulaSetRepository->getOneByName($setName);
            $this->PriceFormulaSetService->choose($PriceFormulaSet);

            restore_error_handler();
        } catch (RepositoryException|ServiceException $Exception) {
            $result = ResultBuilder::buildError($Exception->getMessage());
        } catch (Exception $Exception)  {
            a2wl_print_throwable($Exception);
            $result = ResultBuilder::buildError($Exception->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }

    public function ajaxRemoveSet(): void
    {
        $this->verifyNonceAjax();

        if (!current_user_can('manage_options')) {
            $result = ResultBuilder::buildError($this->getErrorTextNoPermissions());
            echo wp_json_encode($result);
            wp_die();
        }

        $setName = $_POST['name'];

        a2wl_init_error_handler();

        $result = ResultBuilder::buildOk();

        try {
            if (!$this->PriceFormulaSetRepository->checkExists($setName)) {
                throw new RepositoryException(
                    _x("A price formula set with that name does`t exists. Please choose a different name.",
                        'error text', 'ali2woo')
                );
            }

            $this->PriceFormulaSetRepository->removeOneByName($setName);

            restore_error_handler();
        } catch (RepositoryException $RepositoryException) {
            $result = ResultBuilder::buildError($RepositoryException->getMessage());
        } catch (Exception $Exception)  {
            a2wl_print_throwable($Exception);
            $result = ResultBuilder::buildError($Exception->getMessage());
        }

        echo wp_json_encode($result);
        wp_die();
    }
}

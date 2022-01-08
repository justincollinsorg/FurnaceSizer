<?php
/**
 * Copyright © JustinCollins.org. All rights reserved.
 * See COPYING.txt for license details.
 */
declare (strict_types = 1);
namespace Ecommerce121\Fsizer\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Category;
use Ecommerce121\Fsizer\Api\FsizerRepositoryInterface;
use Ecommerce121\Fsizer\Model\FsizerFactory;

/**
 * Class AddFeatureCategoryAttribute
 */
class CreateDefaultFsizerBlock implements DataPatchInterface
{
    /**
     * constant
     */
    const ATTRIBUTE_CODE = 'select_fsizer';

    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     * @param FsizerRepositoryInterface $fsizerRepositoryInterface
     * @param FsizerFactory $fsizerFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger,
        FsizerRepositoryInterface $fsizerRepositoryInterface,
        FsizerFactory $fsizerFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
        $this->fsizerRepositoryInterface = $fsizerRepositoryInterface;
        $this->fsizerFactory = $fsizerFactory;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * This function is responsible for create feature attribute to category
     *
     * @return AddFeatureCategoryAttribute|void
     */
    public function apply()
    {
        $contentValue = <<<TEXT
<input type="hidden" id="setSystem" value="furnace" />
<div class="j_ j_container j_py-0" id="j_quickSizer" style="visibility: hidden; display:none;">
    <div class="j_ j_py-0 j_text-center">
        <h2 id="j_quickSizerHeading" class="j_ j_orangeText j_pb-2 j_my-0 j_pt-0">QUICK SYSTEM SELECTOR</h2>
    </div>
    <main class="j_">
        <div class="j_">
            <div class="j_" id="advancedMenu">
                <button id="advancedBTN" class="j_ j_btn j_btn-light" type="submit">
                    Advanced &nbsp;
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="j_ bi bi-arrow-down-circle" viewBox="0 0 16 16">
                        <path
                            fill-rule="evenodd"
                            d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"
                        />
                    </svg>
                </button>
            </div>
        </div>
        <form class="j_">
            <div class="j_ j_form-group" style="clear: both;">
                <div class="j_ j_row j_g-1 j_d-flex j_justify-content-around j_justify-content-md-center">
                    <div class="j_ j_col-md-4 j_text-center j_md-mt-0" id="j_zipCodeBlock">
                        <label for="zipCode" class="j_ j_form-label j_mt-0 j_quickSizerLabel">ZIP CODE</label>
                        <input onclick="revealFlow()" data-loc-store="zipcode" type="text" pattern="\d*" class="j_ j_form-control" id="zipCode" placeholder="Zip Code" required />

                        <div id="zipValidity" class="j_" style="display: none;">
                            Zip code required.
                        </div>
                    </div>
                    <div class="j_ j_col-md-4 j_text-center j_md-mt-0" id="j_squareFeetBlock">
                        <label for="squareFeet" class="j_ j_form-label j_mt-0 j_quickSizerLabel">HOUSE SQUARE FOOTAGE</label>
                        <input onclick="revealFlow()" data-loc-store="squareFeet" type="number" min="600" max="3333" pattern="\d*" class="j_ j_form-control" id="squareFeet" placeholder="0" value="" required />
                        <div id="squareFeetValidity" class="j_" style="display: none;">
                            Please choose between 600-3333 sqft.
                        </div>
                    </div>
                    <div class="j_ j_col-md-4 j_text-center j_md-mt-0" id="j_systemBlock">
                        <label for="j_System" class="j_ j_form-label j_mt-0 j_quickSizerLabel"> WHAT DO YOU NEED?</label>
                        <select onChange="revealFlowFromSelect()" class="j_ j_form-control j_form-select" id="j_System" required>
                            <option value="ac">Air Conditioner</option>
                            <option value="furnace">Furnace</option>
                            <option value="acfurnace">Furnace and Air Conditioner</option>
                            <option value="heatpump">Heat Pump</option>
                            <option value="heatpumpairhandler">Heat Pump Split System</option>
                        </select>
                        <div class="j_ j_invalid-feedback">
                            Please Select a System.
                        </div>
                    </div>
                </div>
                <div id="advancedContainer" class="j_ j_container" style="display: none;">
                    <div class="j_ j_controls">
                        <div class="j_ j_row j_row2 j_mt-2">
                            <div class="j_ j_col-md-6 j_col-lg-4">
                                <div id="sunOptions" class="j_ j_form-group j_border-bottom">
                                    <label for="j_System" class="j_ j_form-label j_h5-white">
                                        Sun &nbsp;
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="j_ j_bi bi-brightness-high" viewBox="0 0 16 16">
                                            <path
                                                d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                        </svg>
                                    </label>
                                    <div class="j_ j_d-flex j_justify-content-around">
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="sun1" class="j_ j_form-check-input" type="radio" name="sunopt" id="sun1" value="-0.1" checked />
                                            <label class="j_ j_form-check-label" for="sun1">
                                                Low
                                            </label>
                                        </div>
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="sun2" class="j_ j_form-check-input" type="radio" name="sunopt" id="sun2" value="0" checked />
                                            <label class="j_ j_form-check-label" for="sun2">
                                                Average
                                            </label>
                                        </div>
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="sun3" class="j_ j_form-check-input" type="radio" name="sunopt" id="sun3" value="0.1" />
                                            <label class="j_ j_form-check-label" for="sun3">
                                                High
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="j_ j_col-md-6 j_col-lg-4">
                                <div id="windowNumberOptions" class="j_ j_form-group j_border-bottom">
                                    <label class="j_ j_form-label j_h5-white">Number Of Windows</label>
                                    <div class="j_ j_d-flex j_justify-content-around">
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="windowsFew" class="j_ j_form-check-input" type="radio" name="windowsOpt" id="windowsFew" value="-0.1" />
                                            <label class="j_ j_form-check-label" for="windowsFew">
                                                Few
                                            </label>
                                        </div>
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="windowsAverage" class="j_ j_form-check-input" type="radio" name="windowsOpt" id="windowsAverage" value="0" checked />
                                            <label class="j_ j_form-check-label" for="windowsAverage">
                                                Average
                                            </label>
                                        </div>
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="windowsMany" class="j_ j_form-check-input" type="radio" name="windowsOpt" id="windowsMany" value="0.1" />
                                            <label class="j_ j_form-check-label" for="windowsMany">
                                                Many
                                            </label>
                                        </div>
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="windowsSunrooms" class="j_ j_form-check-input" type="radio" name="windowsOpt" id="windowsSunrooms" value="0.5" />
                                            <label class="j_ j_form-check-label" for="windowsSunrooms">
                                                Sunrooms
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="j_ j_col-md-6 j_col-lg-4">
                                <div id="wallInsulationOptions" class="j_ j_form-group">
                                    <label for="j_System" class="j_ j_form-label j_h5-white">Wall Insulation</label>
                                    <select data-loc-store="insulation" class="j_ j_form-select" id="insulation" required>
                                        <option value="-0.1">Best</option>
                                        <option value="-0.05">Good</option>
                                        <option value="0" selected>Average</option>
                                        <option value="0.05">Poor</option>
                                        <option value="0.1">Worst</option>
                                    </select>
                                    <div class="j_ j_invalid-feedback">
                                        Please provide insulation amount.
                                    </div>
                                </div>
                            </div>
                            <div class="j_ j_col-sm-6 j_col-md-4 j_col-lg-2">
                                <div id="sunOptions" class="j_ j_form-group j_border-bottom">
                                    <label class="j_ j_form-label j_h5-white">Kitchen</label>
                                    <div class="j_ j_d-flex j_justify-content-around">
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="kitchenYes"  class="j_ j_form-check-input" type="radio" name="kitchenopt" id="kitchenYes" value="4000" checked />
                                            <label class="j_ j_form-check-label" for="kitchenYes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="j_ j_form-check">
                                            <input data-loc-store="kitchenNo" class="j_ j_form-check-input" type="radio" name="kitchenopt" id="kitchenNo" value="0" />
                                            <label class="j_ j_form-check-label" for="kitchenNo">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="j_ j_col-sm-6 j_col-md-4 j_col-lg-2">
                                <div class="j_ j_form-group">
                                    <label for="people" class="j_ j_form-label j_h5-white">Number of People</label>
                                    <input data-loc-store="people"  id="people" type="number" pattern="\d*" class="j_ j_form-control" placeholder="2" value="2" />
                                    <div class="j_ j_help-block j_with-errors"></div>
                                </div>
                            </div>
                            <div class="j_ j_col-md-6 j_col-lg-4">
                                <div id="winDoorInsulationDiv" class="j_ j_form-group">
                                    <label for="winDoorInsulation" class="j_ j_form-label j_h5-white">Window Insulation</label>
                                    <select data-loc-store="winDoorInsulation" class="j_ j_form-select" id="winDoorInsulation" required>
                                        <option value="-0.1">Great</option>
                                        <option value="0" selected>Average</option>
                                        <option value="0.1">Poor</option>
                                        <option value="0.2">Nonexistant</option>
                                    </select>
                                    <div class="j_ j_invalid-feedback">
                                        Please Select a System.
                                    </div>
                                </div>
                            </div>

                            <div class="j_ j_col-md-6 j_col-lg-4">
                                <div class="j_ j_form-group">
                                    <label for="CeilingHeight" class="j_ j_form-label j_h5-white">Ceiling Height</label>
                                    <input data-loc-store="ceilingHeight"  id="ceilingHeight" type="number" pattern="\d*" class="j_ j_form-control" placeholder="8" value="8" />
                                    <div class="j_ j_help-block j_with-errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="j_ j_clearfix"></div>
                </div>
                <div id="systemsContainer" class="j_ j_container j_text-center" style="display:none">
                    <h5 class="j_ j_h5-white">SELECT SYSTEM AIRFLOW DIRECTION</h5>
                    <div class="j_ j_row j_d-flex j_justify-content-around j_row2">
                        <div class="j_ j_col-6 j_col-sm-3">
                            <div id="imgCheckbox1" class="j_ j_custom-control j_custom-checkbox j_image-checkbox j_text-center">
                                <input data-loc-store="ck1a" type="radio" name="radioFlow" class="j_ j_custom-control-input" id="ck1a" />
                                <label class="j_ j_custom-control-label" for="ck1a">
                                    <span class="j_ j_span-custom-control-label" id="custom-control-label1">Upflow</span>
                                    <img id="base64_upflow" src="" alt="upflow" class="j_ j_img-fluid j_img-thumbnail" />
                                    
                                </label>
                            </div>
                        </div>
                        <div class="j_ j_col-6 j_col-sm-3">
                            <div id="imgCheckbox2" class="j_ j_custom-control j_custom-checkbox j_image-checkbox j_text-center">
                                <input data-loc-store="ck1b" type="radio" name="radioFlow" class="j_ j_custom-control-input" id="ck1b" />
                                <label class="j_ j_custom-control-label" for="ck1b">
                                    <span class="j_ j_span-custom-control-label" id="custom-control-label2">Downflow</span>
                                    <!--<img src="downflow-img-m.png" alt="#" class="j_ j_img-fluid j_img-thumbnail" />-->
                                     <img id="base64_downflow" src='' alt="Down Flow" class="j_ j_img-fluid j_img-thumbnail">
                                </label>
                            </div>
                        </div>
                        <div class="j_ j_col-6 j_col-sm-3">
                            <div id="imgCheckbox3" class="j_ j_custom-control j_custom-checkbox j_image-checkbox j_text-center">
                                <input data-loc-store="ck1c" type="radio" name="radioFlow" class="j_ j_custom-control-input" id="ck1c" />
                                <label class="j_ j_custom-control-label" for="ck1c">
                                    <span class="j_ j_span-custom-control-label" id="custom-control-label3">Horizontal</span>
                                    <!--<img src="horizontal-img-m.jpeg" alt="#" class="j_ j_img-fluid j_img-thumbnail" />-->
                                    <img id="base64_horizontal" src='' alt="Horizontal" class="j_ j_img-fluid j_img-thumbnail">
                                </label>
                            </div>
                        </div>
                        <div class="j_ j_col-6 j_col-sm-3">
                            <div id="imgCheckbox4" class="j_ j_custom-control j_custom-checkbox j_image-checkbox j_text-center">
                                <input data-loc-store="ck1d" type="radio" name="radioFlow" class="j_ j_custom-control-input" id="ck1d" />
                                <label class="j_ j_custom-control-label" for="ck1d">
                                    <span class="j_ j_span-custom-control-label" id="custom-control-label4">Show All</span>
                                    <!--<img src="ALL.png" alt="#" class="j_ j_img-fluid j_img-thumbnail" />-->
                                    
                                    <img id="base64_all" src='' alt="All" class="j_ j_img-fluid j_img-thumbnail">

                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="j_ j_row">
                    <div id="submitBtnLabel" class="j_ j_col-12 j_text-center j_mx-auto j_py-0 j_my-0" style="display: none;"></div>
                </div>
                <div class="j_ j_d-flex j_justify-content-center">
                    <a onclick="validateInputs()" class="j_ j_btn j_btn-light j_btn-md j_mb-3 j_mt-0" id="showResultsButton">Show Results</a>
                </div>
            </div>
        </form>
    </main>
</div>
<div id="j_tool"></div>
TEXT;
        $createdFsizer = $this->fsizerFactory->create();
        $createdFsizer->setData("description","Default");
        $createdFsizer->setData("content",$contentValue);
        $createdFsizer->setData("is_active",1);
        $this->fsizerRepositoryInterface->save($createdFsizer);
    }
}

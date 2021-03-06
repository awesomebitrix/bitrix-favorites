<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);

$elementID = null;
$arErrors = [];

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (!empty($_POST['ENTITY_ID']))
        {
            $elementID = (int)$_POST['ENTITY_ID'];
            
            if ($elementID < 1)
                $arErrors[] = 'Not specified item ID';
        }
        
        if (empty($_POST['ACTION']))
            $arErrors[] = 'Unknown action';
    }
    else
    {
        $arErrors[] = 'No post data';
    }
}
else
{
    $arErrors[] = 'Not ajax';
}

if (!empty($arErrors))
{
    exit(json_encode([
        'action' => $_POST['ACTION'],
        'success' => false,
        'errors' => $arErrors,
    ]));
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

\CBitrixComponent::includeComponentClass('serginhold:favorites.button');

$oComponent = new FavoritesButton;
$oComponent->arParams = $oComponent->onPrepareComponentParams([
    'ENTITY_ID' => $elementID,
    'ENTITY_TYPE' => (string)$_POST['ENTITY_TYPE'],
    'ACTION' => (string)$_POST['ACTION'],
]);

$oComponent->executeComponent();
<?php

use Helper\UI\Widget\Enum\WidgetId;
use Helper\UI\Widget\UI;
use Helper\UI\Widget\AbstractWidget;
use Helper\UI\Widget\Floatbar\SpecialOfferBarWidget;

use Helper\UI\Widget\Modal\LatestNewsWidget;
use Helper\UI\Widget\Modal\NotificationWidget;

use Helper\UI\Widget\Modal\SpecialOfferModalWidget;


// how to add 4 widgets in collection
addWidget(new LatestNewsWidget());
addWidget(new SpecialOfferModalWidget($user /* user entity */));
addWidget(new SpecialOfferBarWidget($user /* user entity */));
addWidget(new NotificationWidget($notifications /*collection of notifications*/));

// just helpers
function addWidget(AbstractWidget $widget)
{
    ui()->widgets()->add($widget);
}

function ui(): UI
{
    return UI::getInstance();
}


// How to render in view
ui()
    ->widgets()
    ->always(WidgetId::LATEST_NEWS)
    ->renderWith(WidgetId::SPECIAL_OFFER_FLOATBAR, WidgetId::LATEST_NEWS)
    ->renderWith(WidgetId::SPECIAL_OFFER_MODAL, WidgetId::LATEST_NEWS)
    ->renderWhenClosed(WidgetId::SPECIAL_OFFER_FLOATBAR, WidgetId::SPECIAL_OFFER_MODAL)
    ->renderOne();

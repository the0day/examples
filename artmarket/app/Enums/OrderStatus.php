<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self payment()    - Waiting for a payment from buyer
 * @method static self accepting()  - Waiting for accepting from seller
 * @method static self accepted()   - Accepted by seller / work in progress
 * @method static self approving()  - Waiting for approving the uploaded work from seller by buyer
 * @method static self declined()   - Declined by seller / it needs corrections
 * @method static self cancelling() - Cancellation initiated by seller or buyer
 * @method static self cancelled()  - Cancelled by system or buyer or seller
 * @method static self delivered()  - The uploaded work approved by buyer
 *
 * New order —— payment —— accepting —— accepted —— approving —— delivered
 *                  \          \            \         \——declined——/
 *                   \          \            \         \
 *                    \          \            \———— cancelling —— cancelled
 *                     \ ———————— \ ————————————————————————————————/
 *
 * The order can be cancelled by those ways:
 * 1) No payment within 36 hours
 * 2) Seller decided not take the order
 * 3) When one side decided cancelling the order and other side accepted it
 *
 * When cancelling can be initiated:
 * 1) Order accepted, but seller did not upload a final work until deadlines
 * 2) Buyer decided cancel the order through arbitrage
 * 3) Seller decided cancel the order
 */
final class OrderStatus extends Enum
{

}

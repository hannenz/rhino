<?php
declare(strict_types=1);

namespace Rhino\Model\Entity;

use Cake\ORM\Entity;

/**
 * Widget Entity
 *
 * @property int $id
 * @property string $name
 */
class Widget extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
    ];
}

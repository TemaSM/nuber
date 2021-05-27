<?php
/**
 * Nuber.io
 * Copyright 2020 - 2021 Jamiel Sharief.
 *
 * SPDX-License-Identifier: AGPL-3.0
 *
 * @copyright   Copyright (c) Jamiel Sharief
 * @link        https://www.nuber.io
 * @license     https://opensource.org/licenses/AGPL-3.0 AGPL-3.0 License
 */

$statusMap = [
    'Running' => 'success',
    'Stopped' => 'secondary',
    'Frozen' => 'warning',
];

$id = 'instance-' . uid();

?>
<tr id="<?= $id ?>">
    <td>
        <div class="instance">
            <a href="/instances/details/<?= $instance['name'] ?>"><?= $instance['name'] ?></a>&nbsp;
        </div>
    </td>

    <td><?php

        $addresses = explode(',', $instance['meta']['ipAddress']);

        foreach ($addresses as $address) {
            echo $this->Html->div("<span>{$address}</span>", ['class' => 'selectable']);
        }
      
    ?></td>
    <td>
        <?php $usage = $this->LxdInstance->memoryUsage($instance); ?>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: <?= $usage ?>%" aria-valuenow="<?= $usage ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
       
    </td>
    <td>
        <div class="progress progress-disk" data-instance="<?= $instance['name'] ?>">
            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <?php
            if ($instance['status'] === 'Running') :
        ?> 
        <script> 
            updateDiskUsage('<?= $instance['name'] ?>','<?= $id ?>');
        </script>
        <?php endif ?>
    </td>

    <?php
        $standardAttributes = 'class="dropdown-item"';
        $disabledAttributes = 'class="dropdown-item disabled" aria-disabled="true"';
    ?>
   <td id="<?= $id .'-status' ?>">
        <span class="badge badge-<?= $statusMap[$instance['status']]; ?>"><?= $instance['status']; ?></span>
        <div class="spinner-border text-primary " style="width: 2rem; height: 2rem;" role="status">
            <span class="sr-only">Loading...</span>
            </div>
    </td>
    <td><?= $this->Date->timeAgoInWords($this->LxdInstance->convertISOdate($instance['created_at'])) ?></td>
    <td>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= __('Actions') ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
               
                <a <?= $instance['status'] === 'Stopped' ? $standardAttributes : $disabledAttributes ?> onClick="start('<?= $instance['name'] ?>','<?= $id ?>')" href="#">
                    <?= __('Start') ?>
                </a>

                <a <?= $instance['status'] === 'Running' ? $standardAttributes : $disabledAttributes ?> onClick="stop('<?= $instance['name'] ?>','<?= $id ?>')" href="#">
                    <?= __('Stop') ?>
                </a>
                <a <?= $instance['status'] === 'Running' ? $standardAttributes : $disabledAttributes ?> onClick="restart('<?= $instance['name'] ?>','<?= $id ?>')" href="#">
                    <?= __('Restart') ?>
                </a>
            </div>
        </div>
        
    </td>
</tr>
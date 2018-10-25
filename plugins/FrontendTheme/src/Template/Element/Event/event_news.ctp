<div class="box-messages">
    <h4 class="mb-20"><?='EVENT '.$symbol;?></h4>
</div>
<div class="sector-list ">
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
            <tr>
                <th class="fw-30">Date</th>
                <th class="text-center">Location</th>
                <th class="text-center">Title</th>
                <th class="text-center">Activity</th>
                <th class="text-center">Description</th>
                <th class="text-center"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($events as $event):?>
            <tr>
                <td class="text-center"><?=$event->date->nice();?></td>
                <td class="text-center"><?=$event->location;?></td>
                <td class="text-center"><?=$event->title;?></td>
                <td class="text-center"><?=$event->activity_type;?></td>
                <td class="text-center"><?=$event->description;?></td>
                <td class="text-center">
                    <?php if ($accountType != 'FREE'): ?>
                        <?php if(!empty($event->meeting_link)):?>
                            <a href="<?= $event->meeting_link; ?>" class="btn btn-success btn-sm">
                                <i class="fa fa-edit white"></i>Join now
                            </a>
                        <?php endif;?>
                    <?php else: ?>
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target=".bs-subscription-modal-full">
                            <i class="fa fa-edit white"></i>Join now
                        </button>
                    <?php endif;?>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
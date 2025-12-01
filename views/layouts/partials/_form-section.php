<section class="form row">
    <div class="col-xl-3 form-title">
        <h5><?= $title ?? '' ?></h5>
    </div>

    <div class="<?= $class ?? 'col-xl-9' ?>">
        <?= $content ?? '' ?>
    </div>
</section>
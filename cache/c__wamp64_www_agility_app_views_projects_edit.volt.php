<?php
/**
 * @var \Phalcon\Mvc\View\Engine\Php $this
 */
?>

<div class="row">
    <nav>
        <ul class="pager">
            <li class="previous"><?php echo $this->tag->linkTo(["projects", "Back"]) ?></li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h1>
        Edit projects
    </h1>
</div>

<?php echo $this->getContent(); ?>

<?php
    echo $this->tag->form(
        [
            "projects/save",
            "autocomplete" => "off",
            "class" => "form-horizontal"
        ]
    );
?>

<div class="form-group">
    <label for="fieldName" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["name", "size" => 30, "class" => "form-control", "id" => "fieldName"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldDescription" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textArea(["description", "cols" => 30, "rows" => 4, "class" => "form-control", "id" => "fieldDescription"]) ?>
    </div>
</div>

<div class="form-group">
    <label for="fieldStarts" class="col-sm-2 control-label">Starts</label>
    <div class="col-sm-10">
        <?php echo $this->tag->textField(["starts", "size" => 30, "class" => "form-control", "id" => "fieldStarts"]) ?>
    </div>
</div>

<?php echo $this->tag->hiddenField("id") ?>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <?php echo $this->tag->submitButton(["Save", "class" => "btn btn-primary"]) ?>
        <?= $this->tag->linkTo(['projects/', 'Cancel']) ?>
    </div>
</div>

<?php echo $this->tag->endForm(); ?>

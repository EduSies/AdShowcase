<?php

/** @var yii\web\View $this */
/** @var app\models\forms\back_office\ProductForm $model */
/** @var array $indexRoute */

use yii\bootstrap5\ActiveForm;

$isUpdate = !empty($model->hash);

?>

<div class="<?= $model->formName() ?>">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'validateOnChange' => true,
        'options' => ['autocomplete' => 'off'],
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback d-block'],
        ],
    ]); ?>

    <?= $this->render('@adshowcase.layouts/partials/_form-section', [
            'title' => Yii::t('app', 'Product'),
            'indexRoute' => $indexRoute,
            'content' => $this->render('form_sections/_content', [
                'model' => $model,
                'form' => $form,
                'isUpdate' => $isUpdate,
            ])
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<<JS

    (function() {
      // Use the real form ID coming from PHP to scope the selectors
      var formId = '{$model->formName()}';
      var form = document.getElementById(formId) || document.querySelector('form#' + CSS.escape(formId));
      if (!form) {
        console.warn('ProductForm: form not found', formId);
        return;
      }
    
      // Robust selectors: match inputs by their "name" attribute suffix (works for AR and FormModel)
      var nameInput = form.querySelector('input[name$="[name]"]');
      var slugInput = form.querySelector('input[name$="[url_slug]"]');
    
      if (!nameInput || !slugInput) {
        console.warn('ProductForm: inputs not found', { haveName: !!nameInput, haveSlug: !!slugInput });
        return;
      }
    
      // Prevent manual edits but allow programmatic assignment
      slugInput.readOnly = true;
    
      function slugify(v) {
        return (v || '')
          .toString().trim().toLowerCase()
          .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // remove accents
          .replace(/[^a-z0-9]+/g, '-')                      // non-alnum => dash
          .replace(/^-+|-+$/g, '');                         // trim edge dashes
      }
    
      function updateSlug() {
        // Only overwrite if empty OR if it's exactly the previous auto-generated value.
        // This avoids overriding manual edits on subsequent typing.
        if (!slugInput.dataset.userEdited || slugInput.value.trim() === '') {
          slugInput.value = slugify(nameInput.value);
        }
      }
    
      // Detect manual user edits on slug (even if readOnly is later toggled off)
      slugInput.addEventListener('input', function() { slugInput.dataset.userEdited = '1'; });
    
      // Initial fill if empty
      if (!slugInput.value.trim()) {
        updateSlug();
      }
    
      // Keep in sync with name changes
      nameInput.addEventListener('input', updateSlug);
      nameInput.addEventListener('change', updateSlug);
      nameInput.addEventListener('blur', updateSlug);
    })();

JS;

$this->registerJs($js);

?>
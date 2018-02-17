<div
  class="form-group">
  <label for="inputKeywords">Search</label>
  <input
    value="<?php echo $this->getRequest('keywords') ?? $app->getMeta('DEFAULT_KEYWORDS'); ?>"
    type="text" id="inputKeywords" name="keywords" class="form-control"
    placeholder="Keywords"
    required autofocus>
</div>
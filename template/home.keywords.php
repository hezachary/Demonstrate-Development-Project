<div
  class="form-group">
  <label for="inputKeywords">Search</label>
  <input
    value="<?php echo $this->getRequest('keywords') ?? $app->getMeta('AUTHOR'); ?>"
    type="text" id="inputKeywords" name="keywords" class="form-control"
    placeholder="Keywords"
    required autofocus>
</div>
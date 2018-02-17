<div
  class="form-group">
  <label for="inputMatchwords">Also Matches</label>
  <input
    value="<?php echo $app->getMeta('DEFAULT_MATCHWORDS'); ?>"
    type="text" id="inputMatchwords" name="matchwords" class="form-control"
    placeholder="Match words"
    required autofocus>
</div>
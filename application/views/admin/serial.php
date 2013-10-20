<form action="/admin/serial/create" method="post">
  <label>Number of Serial</label>
  <input class="form-control" type="text" name="number" placeholder="Number of serial" value="1" />
  <label>Prefix</label>
  <input class="form-control" type="text" name="prefix" placeholder="Prefix of serial" value="A0T0" />
  <label>Segment</label>
  <input class="form-control" type="text" name="segment" placeholder="Segment number of serial" value="3" />
  <label>Length</label>
  <input class="form-control" type="text" name="length" placeholder="Length of each segment" value="5"/>
  <button type="submit" class="btn btn-default">Creage</button>
</form>
<div id="processingImportLoading" class="justify-content-center my-2" style="display: none;">
  <span class="spinner-border" role="status"></span>
</div>
<div data-controller="importer" data-importer-url-value="{{ url('/asyncGetImportStatus') }}" data-importer-refresh-interval-value="10000" class="alert alert-info rounded shadow-sm mb-3 p-3 d-flex">
    processando...
</div>
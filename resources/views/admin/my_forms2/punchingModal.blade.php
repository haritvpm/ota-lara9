<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
    <!-- Modal -->
<div class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
     
        <slot name="header">
             default header
        </slot>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <slot name="body">
          <!-- default body -->
        </slot>
      </div>
      <div class="modal-footer">
          <slot name="footer">
            <!-- default footer -->
            <button type="button" class="btn btn-secondary" @click="$emit('close')">
            Close
            </button>
          </slot>
       </div>
    </div>
  </div>
</div>

</script>
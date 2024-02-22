<!-- template for the modal component -->
<script type="text/x-template" id="modal-template">
      <transition name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div  class="modal-container">

              <div class="modal-header">
                <slot name="header">
                  -----
                </slot>
                <button type="button" class="close" @click="$emit('close')" >
                <span aria-hidden="true">&times;</span>
              </button>
              </div>

              <div class="modal-body">
                <slot name="body">
               
                </slot>
              </div>

              <div class="modal-footer d-flex justify-content-between">
                <slot name="footer">
                  <!--enter here default footer -->
                 
                </slot>
                <button type="button" class="btn btn-secondary" @click="$emit('close')">
                    OK
                  </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/js/scripts.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        
         <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


        <script>
              $(document).ready(function() {
                    // $('#add_prescription_modal').modal('show');
                     var lastChecked;

                    $('.row-checkbox').click(function(e) {
                        if (e.shiftKey) {
                            var checkboxes = $('.row-checkbox');
                            var currentIdx = checkboxes.index(this);
                            if (lastChecked !== undefined && lastChecked !== null) {
                                var lastIndex = checkboxes.index(lastChecked);
                                var startIdx = Math.min(currentIdx, lastIndex);
                                var endIdx = Math.max(currentIdx, lastIndex);

                                for (var i = startIdx; i <= endIdx; i++) {
                                    checkboxes.eq(i).prop('checked', true);
                                }
                            }
                        }
                        lastChecked = this;
                                
                      });
                 
              });
        </script>

        @yield('pages_specific_scripts')

        
    </body>
</html>


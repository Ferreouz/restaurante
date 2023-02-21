@if(session()->has('message'))
@php($erro = str_contains(session('message'), 'rro'))
    <div style="z-index: 200" id="toast" role="alert" aria-atomic="true" class="toast fade show position-fixed bottom-0 end-0 p-3" aria-live="polite">


            <div class="toast-header">
            <strong class="me-auto" style="color: var(--primary-60);">Bot</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body fw-bold @if($erro) text-danger  @else text-success @endif">
            {{ session('message') }}
            </div>

    </div>
    {{-- @push('scripts')
        <script>
            window.onload = (event) => {
            const myAlert = document.getElementById('toast');
          

            const bsAlert = new bootstrap.Toast(myAlert);
            setTimeout(() => {
                
                bsAlert.hide();
            }, 2000);
        }
        </script>
    @endpush --}}
    <script>
            let myAlert = document.getElementById('toast');
            let bsAlert = new bootstrap.Toast(myAlert);
  

            function checkToast(){
                if(bsAlert.isShown){
                    bsAlert.hide();
                } 
            }
            
            setInterval(checkToast, 3000);
     </script>

@endif
@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<div class="page-content">
        <!-- PAGE-HEADER -->
        @include('layouts/pageContentHeader/index')
        <!-- PAGE-HEADER END -->
        
        <div class="card">
            <div class="card-body">
                <iframe src="https://servicecopilot.microsoft.com/environments/5eab483f-8cd6-e43e-8eaa-2e2a1bc5eb0d/copilots/msdyn_AgentCopilot/webchat" frameborder="0" style="width: 100%; height: 100%; min-height: 500px; border-color: none;"></iframe>
            </div>
        </div>
        
    </div>
</div>
@stop

@section('pages_specific_scripts')
<style>
    

</style>
<script>
    $(document).ready(function() {
        
    });

</script>
@stop
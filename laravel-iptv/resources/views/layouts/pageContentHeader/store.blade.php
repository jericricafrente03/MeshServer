<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">
        {{ isset($menuStores[request()->id]) ? $menuStores[request()->id]->code : '' }}
    </div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="/store/bulletin/{{request()->id}}/dashboard"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item">{{$breadCrumb[0]}}</li>
                @if (isset($breadCrumb[2]))
                    <li class="breadcrumb-item">
                        <a @class([isset($breadCrumb['back']) ? '': 'text-secondary']) href="{{isset($breadCrumb['back']) ? $breadCrumb['back'] : '#'}}">{{$breadCrumb[1]}}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{$breadCrumb[2]}}</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{$breadCrumb[1]}}</li>
                @endif
            </ol>
        </nav>
    </div>
</div>
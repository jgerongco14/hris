<ul class="nav nav-tabs" id="contributionTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeType === 'SSS' ? 'active' : '' }}" id="sss-tab"
            data-bs-toggle="tab" data-bs-target="#sss" type="button" role="tab"
            aria-controls="sss" aria-selected="{{ $activeType === 'SSS' ? 'true' : 'false' }}">SSS</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeType === 'PAG-IBIG' ? 'active' : '' }}" id="pagibig-tab"
            data-bs-toggle="tab" data-bs-target="#pagibig" type="button" role="tab"
            aria-controls="pagibig" aria-selected="{{ $activeType === 'PAG-IBIG' ? 'true' : 'false' }}">PAG-IBIG</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeType === 'TIN' ? 'active' : '' }}" id="tin-tab"
            data-bs-toggle="tab" data-bs-target="#tin" type="button" role="tab"
            aria-controls="tin" aria-selected="{{ $activeType === 'TIN' ? 'true' : 'false' }}">TIN</button>
    </li>
</ul>

@extends('layouts.restaurant')

@section('title', 'Table Schedule')

@section('content')
<div class="d-flex flex-column" style="min-height: calc(100vh - 70px); background-color: #ffffff;">
    
    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom bg-white">
        <h2 class="h4 mb-0 fw-bold text-dark" style="color: #0A2540 !important;">Table Schedule</h2>
        
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-light btn-sm border text-secondary px-2 py-1"><i class="fa-solid fa-chevron-left"></i></button>
            <input type="text" class="form-control form-control-sm text-center fw-bold" value="2026/05/20" style="width: 130px; background-color: #f8f9fa;" readonly>
            <button class="btn btn-light btn-sm border text-secondary px-2 py-1"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center px-4 py-2 bg-white border-bottom small text-secondary">
        <button class="btn btn-link text-secondary p-0"><i class="fa-solid fa-chevron-left"></i></button>
        <span class="fw-medium">Showing 17:00 - 18:45</span>
        <button class="btn btn-link text-secondary p-0"><i class="fa-solid fa-chevron-right"></i></button>
    </div>

    <div class="d-flex flex-grow-1" style="overflow-x: auto;">
        
        <div class="bg-white border-end p-3 d-flex flex-column gap-3" style="width: 320px; min-width: 320px; overflow-y: auto;">
            
            <div>
                <h6 class="fw-bold text-dark mb-3">Reservations (2)</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="card p-3 border rounded-3 position-relative shadow-sm" style="background-color: #ffffff;">
                        <span class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light" style="font-size: 10px;">RM001</span>
                        <div class="fw-bold text-dark mb-1">John Smith</div>
                        <div class="text-secondary small d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock"></i> 18:00
                            <i class="fa-solid fa-user-group ms-2"></i> 2
                        </div>
                        <div class="text-secondary small mt-1">Table 1</div>
                    </div>

                    <div class="card p-3 border rounded-3 position-relative shadow-sm" style="background-color: #ffffff;">
                        <span class="badge text-secondary border position-absolute top-0 end-0 mt-2 me-2 small fw-normal bg-light" style="font-size: 10px;">RM002</span>
                        <div class="fw-bold text-dark mb-1">Maria Garcia</div>
                        <div class="text-secondary small d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock"></i> 18:00
                            <i class="fa-solid fa-user-group ms-2"></i> 4
                        </div>
                        <div class="text-secondary small mt-1">Table 2</div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <h6 class="fw-bold text-secondary mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Canceled</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                        <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small" style="font-size: 10px;">RM005</span>
                        <div class="fw-bold text-secondary mb-1">Michael Chen</div>
                        <div class="text-danger small fw-medium">Canceled by customer</div>
                        <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                            <i class="fa-regular fa-clock"></i> 17:30
                            <i class="fa-solid fa-user-group ms-2"></i> 3
                        </div>
                    </div>

                    <div class="card p-3 border border-dashed rounded-3 position-relative bg-light opacity-75">
                        <span class="text-muted position-absolute top-0 end-0 mt-2 me-2 small" style="font-size: 10px;">RM006</span>
                        <div class="fw-bold text-secondary mb-1">Emma Wilson</div>
                        <div class="text-danger small fw-medium">Canceled by shop</div>
                        <div class="text-muted small d-flex align-items-center gap-1 mt-1">
                            <i class="fa-regular fa-clock"></i> 19:30
                            <i class="fa-solid fa-user-group ms-2"></i> 2
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-grow-1 bg-light p-3" style="overflow-y: auto;">
            <div class="table-responsive bg-white rounded-3 border">
                <table class="table table-bordered align-middle mb-0 text-center" style="min-width: 800px; border-color: #e9ecef;">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th style="width: 150px; background-color: #f8f9fa;"></th>
                            <th style="font-weight: 500;">17:00</th>
                            <th style="font-weight: 500;">17:15</th>
                            <th style="font-weight: 500;">17:30</th>
                            <th style="font-weight: 500;">17:45</th>
                            <th style="font-weight: 500;">18:00</th>
                            <th style="font-weight: 500;">18:15</th>
                            <th style="font-weight: 500;">18:30</th>
                            <th style="font-weight: 500;">18:45</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start ps-3 bg-light-subtle">
                                <div class="fw-bold text-dark small">Table 1</div>
                                <div class="text-muted" style="font-size: 11px;">2 seats</div>
                            </td>
                            <td></td><td></td><td></td><td></td>
                            <td colspan="4" class="p-1">
                                <div class="text-white text-start p-2 rounded small shadow-sm" style="background-color: #0A2540;">
                                    <div class="fw-bold" style="font-size: 12px;">John Smith</div>
                                    <div style="font-size: 10px; opacity: 0.8;">2 guests</div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start ps-3 bg-light-subtle">
                                <div class="fw-bold text-dark small">Table 2</div>
                                <div class="text-muted" style="font-size: 11px;">4 seats</div>
                            </td>
                            <td></td><td></td><td></td><td></td>
                            <td colspan="4" class="p-1">
                                <div class="text-start p-2 rounded small shadow-sm" style="background-color: #FCE7F3; color: #9D174D;">
                                    <div class="fw-bold" style="font-size: 12px;">Maria Garcia</div>
                                    <div style="font-size: 10px; opacity: 0.8;">4 guests</div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-start ps-3 bg-light-subtle">
                                <div class="fw-bold text-dark small">Table 3</div>
                                <div class="text-muted" style="font-size: 11px;">2 seats</div>
                            </td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>

                        <tr>
                            <td class="text-start ps-3 bg-light-subtle">
                                <div class="fw-bold text-dark small">Table 4</div>
                                <div class="text-muted" style="font-size: 11px;">6 seats</div>
                            </td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>

                        <tr>
                            <td class="text-start ps-3 bg-light-subtle">
                                <div class="fw-bold text-dark small">Table 5</div>
                                <div class="text-muted" style="font-size: 11px;">4 seats</div>
                            </td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div class="position-fixed bottom-0 end-0 m-4">
    <button class="btn rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 56px; height: 56px; background-color: #0A2540; color: #ffffff;">
        <i class="fa-regular fa-comment-dots fs-4"></i>
    </button>
</div>
@endsection
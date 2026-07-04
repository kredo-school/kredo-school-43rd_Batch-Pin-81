@extends('layouts.app')

@section('title', 'Application successful')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class=" border-0 rounded-4 text-center p-5">

                <div class="display-1 mb-3">
                    🎉
                </div>

                <h1 class="fw-bold text-success">
                    Thank You for Applying!
                </h1>

                <p class="fs-5 mt-3">
                    Your restaurant application has been successfully submitted to
                    <strong>PIN+81</strong>.
                </p>

                <p class="text-muted">
                    Our team will carefully review your application.
                    Once the review is complete, we'll send you an email with the result.
                </p>

                <hr class="my-4">

                <p class="mb-4">
                    Thank you for joining our community. We look forward to welcoming
                    your restaurant to PIN+81!
                </p>

                <a href="{{ route('customer.search') }}" class="btn btn-danger px-4">
                    Back to Home
                </a>

            </div>

        </div>
    </div>
</div>

<!-- Confetti -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

<script>
window.onload = function () {
    const duration = 3000;
    const end = Date.now() + duration;

    (function frame() {
        confetti({
            particleCount: 6,
            angle: 60,
            spread: 60,
            origin: { x: 0 }
        });

        confetti({
            particleCount: 6,
            angle: 120,
            spread: 60,
            origin: { x: 1 }
        });

        if (Date.now() < end) {
            requestAnimationFrame(frame);
        }
    })();
};
</script>
@endsection
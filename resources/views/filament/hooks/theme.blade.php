<style>
    .fi-logo {
        height: auto !important;
        width: auto !important;
    }

    .fi-simple-layout {
        background: linear-gradient(160deg, #0f1916 0%, #143028 45%, #1a3d32 100%) !important;
    }

    .fi-simple-main-ctn {
        padding: 1.5rem;
    }

    .fi-simple-main {
        max-width: 26rem !important;
        border-radius: 1.25rem !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        background: rgba(15, 25, 22, 0.92) !important;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.04) !important;
        padding: 2.5rem 2rem !important;
    }

    .fi-simple-header {
        margin-bottom: 0.25rem;
    }

    .fi-simple-header:empty {
        display: none;
        margin: 0;
    }

    .fi-simple-header-heading {
        font-family: Fraunces, Georgia, serif !important;
        font-size: 1.75rem !important;
        font-weight: 600 !important;
        letter-spacing: -0.02em;
        color: #faf7f2 !important;
    }

    .fi-simple-header-subheading {
        max-width: 20rem;
        margin-inline: auto;
        line-height: 1.55;
        color: rgba(250, 247, 242, 0.55) !important;
    }

    .nl-login-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.75rem;
    }

    .nl-login-brand-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .nl-login-mark {
        display: inline-flex;
        height: 3rem;
        width: 3rem;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 0.875rem;
        background: linear-gradient(145deg, #2a6650, #1f4d3c);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.12);
        font-size: 1.125rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .nl-login-name {
        font-size: 1.375rem;
        font-weight: 600;
        letter-spacing: -0.03em;
        color: #faf7f2;
    }

    .nl-login-badge {
        display: inline-block;
        border-radius: 9999px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        padding: 0.25rem 0.75rem;
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(250, 247, 242, 0.5);
    }

    .nl-login-intro {
        margin-bottom: 1.75rem;
        text-align: center;
    }

    .nl-login-heading {
        font-family: Fraunces, Georgia, serif;
        font-size: 1.75rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: #faf7f2;
    }

    .nl-login-subheading {
        margin-top: 0.5rem;
        max-width: 20rem;
        margin-inline: auto;
        font-size: 0.875rem;
        line-height: 1.55;
        color: rgba(250, 247, 242, 0.55);
    }

    .fi-simple-page:has(.nl-login-brand) .fi-simple-header {
        display: none;
    }

    .fi-simple-page:has(.nl-login-brand) > section {
        gap: 0;
    }

    .fi-simple-page .fi-fo-field-wrp-label span,
    .fi-simple-page .fi-fo-field-wrp-label label {
        color: rgba(250, 247, 242, 0.85) !important;
    }

    .fi-simple-page .fi-input-wrp {
        background: rgba(255, 255, 255, 0.04) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15);
    }

    .fi-simple-page .fi-input-wrp:focus-within {
        border-color: rgba(61, 138, 106, 0.55) !important;
        box-shadow: 0 0 0 3px rgba(61, 138, 106, 0.15);
    }

    .fi-simple-page .fi-input {
        color: #faf7f2 !important;
    }

    .fi-simple-page .fi-input::placeholder {
        color: rgba(250, 247, 242, 0.35) !important;
    }

    .fi-simple-page .fi-fo-checkbox-label {
        color: rgba(250, 247, 242, 0.65) !important;
    }

    .fi-simple-page .fi-btn-color-primary {
        background: linear-gradient(180deg, #de8560 0%, #c46840 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 4px 14px rgba(196, 104, 64, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
        font-weight: 600 !important;
        letter-spacing: 0.01em;
    }

    .fi-simple-page .fi-btn-color-primary:hover {
        background: linear-gradient(180deg, #e89570 0%, #d07448 100%) !important;
    }

    .fi-sidebar {
        background: linear-gradient(180deg, #faf7f2 0%, #f4f0e8 100%) !important;
        border-right: 1px solid rgba(21, 34, 32, 0.06) !important;
    }

    .fi-topbar {
        background: rgba(250, 247, 242, 0.9) !important;
        backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(21, 34, 32, 0.06) !important;
    }

    .fi-wi-stats-overview-stat {
        border-radius: 1rem !important;
        border: 1px solid rgba(21, 34, 32, 0.06) !important;
    }

    .fi-btn-color-primary:not(.fi-simple-page .fi-btn-color-primary) {
        background: #1f4d3c !important;
    }

    .fi-btn-color-primary:not(.fi-simple-page .fi-btn-color-primary):hover {
        background: #2a6650 !important;
    }
</style>

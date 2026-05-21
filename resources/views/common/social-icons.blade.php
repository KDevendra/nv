<!-- Sticky Contact (Right Side) -->
<div class="sticky-contact" id="stickyContact" aria-label="Quick contact options">

    <!-- Toggle for mobile -->
    <button class="sticky-toggle" id="stickyToggle"
        aria-label="Open contact options" type="button">

        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M4 4h16v11H7.6L4 18.2V4zm2 2v8.1l1.8-1.1H18V6H6z" />
        </svg>
    </button>

    <!-- WhatsApp -->
    <a class="sticky-item wa"
        href="https://wa.me/919990186086"
        target="_blank"
        rel="noopener"
        aria-label="Chat on WhatsApp">

        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 32 32"
                aria-hidden="true">

                <path fill="#fff"
                    d="M16.01 3C8.83 3 3 8.74 3 15.82c0 2.5.73 4.93 2.11 7L3 29l6.39-2.05a13.1 13.1 0 0 0 6.62 1.79C23.19 28.74 29 23 29 15.92 29 8.84 23.19 3 16.01 3zm0 23.55c-2.02 0-4-.54-5.72-1.56l-.41-.24-3.79 1.21 1.24-3.67-.27-.43a10.46 10.46 0 0 1-1.63-5.64c0-5.78 4.76-10.48 10.59-10.48 5.84 0 10.59 4.7 10.59 10.48 0 5.78-4.75 10.48-10.59 10.48zm5.81-7.86c-.32-.16-1.9-.93-2.2-1.03-.29-.11-.5-.16-.71.16-.21.31-.81 1.03-.99 1.24-.18.21-.36.24-.68.08-.32-.16-1.34-.49-2.55-1.56-.94-.83-1.58-1.85-1.77-2.16-.18-.31-.02-.48.14-.63.14-.14.32-.37.47-.55.16-.18.21-.31.32-.52.1-.21.05-.39-.03-.55-.08-.16-.71-1.71-.98-2.35-.25-.6-.51-.52-.71-.53h-.6c-.21 0-.55.08-.84.39-.29.31-1.11 1.08-1.11 2.63s1.14 3.04 1.3 3.25c.16.21 2.24 3.4 5.43 4.76.76.33 1.35.52 1.81.66.76.24 1.46.2 2.01.12.61-.09 1.9-.78 2.17-1.54.27-.76.27-1.41.19-1.54-.08-.13-.29-.21-.61-.37z" />
            </svg>
        </span>

        <span class="label">WhatsApp</span>
    </a>

    <!-- Call -->
    <a class="sticky-item call"
        href="tel:+919990186086"
        aria-label="Call now">

        <span class="icon">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M6.6 10.8c1.6 3 3.7 5.2 6.6 6.6l2.2-2.2c.3-.3.7-.4 1.1-.3 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V21c0 .6-.4 1-1 1C10.4 22 2 13.6 2 3c0-.6.4-1 1-1h3.9c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2.2 2.2z" />
            </svg>
        </span>

        <span class="label">Call Now</span>
    </a>

    <!-- Email -->
    <a class="sticky-item email"
        href="mailto:info@zendoindia.com"
        aria-label="Send email">

        <span class="icon">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z" />
            </svg>
        </span>

        <span class="label">Email Us</span>
    </a>

</div>

<style>
    .sticky-contact {
        position: fixed;
        right: 12px;
        top: 45%;
        transform: translateY(-50%);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    /* ===== Sticky Item ===== */
    .sticky-item {
        position: relative;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        text-decoration: none;
        overflow: hidden;
        padding-right: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .18);
        transition: all .3s ease;
    }

    /* Hover Expand */
    @media (hover:hover) {
        .sticky-item:hover {
            width: 170px;
            transform: translateX(-6px);
        }

        .sticky-item:hover .label {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Icon Box */
    .sticky-item .icon {
        width: 56px;
        min-width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* SVG Icons */
    .sticky-item svg {
        width: 26px;
        height: 26px;
        fill: #fff;
    }

    /* WhatsApp Bigger Icon */
    .sticky-item.wa svg {
        width: 30px;
        height: 30px;
    }

    /* Labels */
    .sticky-item .label {
        position: absolute;
        right: 52px;
        white-space: nowrap;
        font-size: 15px;
        font-weight: 600;
        color: #fff;
        opacity: 0;
        transform: translateX(10px);
        transition: all .25s ease;
    }

    /* Colors */
    .sticky-item.wa {
        background: #25D366;
    }

    .sticky-item.call {
        background: #3B82F6;
    }

    .sticky-item.email {
        background: #111827;
    }

    /* Toggle Button */
    .sticky-toggle {
        display: none;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        border: none;
        cursor: pointer;
        background: #F59E0B;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .18);
    }

    .sticky-toggle svg {
        width: 24px;
        height: 24px;
        fill: #fff;
    }

    /* Mobile */
    @media (max-width: 768px) {

        .sticky-contact {
            top: auto;
            bottom: 18px;
            transform: none;
        }

        .sticky-toggle {
            display: grid;
            place-items: center;
        }

        .sticky-contact .sticky-item {
            width: 56px;
            height: 56px;
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
        }

        .sticky-contact.is-open .sticky-item {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
    }

    /* Reduced Motion */
    @media (prefers-reduced-motion: reduce) {

        .sticky-item,
        .sticky-item .label {
            transition: none;
        }
    }
</style>

<script>
    const stickyToggle = document.getElementById('stickyToggle');
    const stickyContact = document.getElementById('stickyContact');

    stickyToggle.addEventListener('click', () => {
        stickyContact.classList.toggle('is-open');
    });
</script>
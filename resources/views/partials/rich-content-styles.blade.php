{{--
    Shared styles for rich-text content emitted by Summernote.
    Used by anything rendering sanitized HTML inside a `.rich-content`
    wrapper (events, councils, quiz competitions, ...).

    The legacy selectors `.quiz-description` and `.question-text` are kept
    as aliases so existing markup keeps rendering correctly without churn.
--}}
@once
    <style>
        /* ============================================================
           Rich-text content (Summernote output)
           ============================================================ */
        .rich-content,
        .quiz-description,
        .question-text {
            direction: rtl;
            text-align: right;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .rich-content p,
        .quiz-description p,
        .question-text p {
            margin-bottom: 0.75rem;
        }

        .rich-content p:last-child,
        .quiz-description p:last-child,
        .question-text p:last-child {
            margin-bottom: 0;
        }

        .rich-content strong,
        .rich-content b,
        .quiz-description strong,
        .quiz-description b {
            font-weight: 700;
            color: #16a34a;
        }

        .question-text strong,
        .question-text b {
            font-weight: 700;
        }

        .rich-content em,
        .rich-content i,
        .quiz-description em,
        .quiz-description i,
        .question-text em,
        .question-text i {
            font-style: italic;
        }

        .rich-content u,
        .quiz-description u,
        .question-text u {
            text-decoration: underline;
        }

        .rich-content ul,
        .rich-content ol,
        .quiz-description ul,
        .quiz-description ol,
        .question-text ul,
        .question-text ol {
            margin-right: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .rich-content li,
        .quiz-description li,
        .question-text li {
            margin-bottom: 0.5rem;
        }

        .rich-content a,
        .quiz-description a,
        .question-text a {
            color: #22c55e;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .rich-content a:hover,
        .quiz-description a:hover,
        .question-text a:hover {
            color: #16a34a;
        }

        .rich-content img,
        .quiz-description img,
        .question-text img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }

        .rich-content table,
        .quiz-description table {
            width: 100%;
            margin-bottom: 0.75rem;
            border-collapse: collapse;
        }

        .rich-content table td,
        .rich-content table th,
        .quiz-description table td,
        .quiz-description table th {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .rich-content iframe,
        .quiz-description iframe {
            max-width: 100%;
            border: 0;
        }

        .rich-content h1, .rich-content h2, .rich-content h3,
        .rich-content h4, .rich-content h5, .rich-content h6 {
            font-weight: 700;
            margin: 0.75rem 0 0.5rem;
        }

        .rich-content blockquote {
            border-right: 4px solid #22c55e;
            padding: 0.25rem 0.75rem;
            margin: 0.5rem 0;
            color: #374151;
            background: #f0fdf4;
        }
    </style>
@endonce

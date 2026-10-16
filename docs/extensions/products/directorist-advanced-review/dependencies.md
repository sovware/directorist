# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/composer.json#L1) | wpmvc/framework | 1.1.2 |
| [package.json:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/package.json#L1) | @wordpress/interactivity | ^6.18.0 |
| [package.json:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/package.json#L1) | just-validate | ^4.3.0 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-advanced-review.php:4](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/directorist-advanced-review.php#L4) | defined | `'ABSPATH'` |
| [app/DTO/ReactionDTO.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReactionDTO.php#L5) | defined | `"ABSPATH"` |
| [app/DTO/ReviewDTO.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReviewDTO.php#L5) | defined | `"ABSPATH"` |
| [app/DTO/ReviewReadDTO.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReviewReadDTO.php#L5) | defined | `"ABSPATH"` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Http/Controllers/AttachmentController.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/AttachmentController.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Controllers/Controller.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/Controller.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Controllers/ReactionController.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReactionController.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Controllers/ReplyController.php:6](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReplyController.php#L6) | defined | `"ABSPATH"` |
| [app/Http/Controllers/ReportController.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReportController.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Controllers/ReviewController.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReviewController.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Controllers/UserController.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/UserController.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Middleware/Auth.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Middleware/Auth.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Middleware/EnsureIsUserAdmin.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Middleware/EnsureIsUserAdmin.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Middleware/GuestReview.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Middleware/GuestReview.php#L5) | defined | `"ABSPATH"` |
| [app/Models/AdvancedReview.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/AdvancedReview.php#L5) | defined | `"ABSPATH"` |
| [app/Models/Comment.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/Comment.php#L5) | defined | `"ABSPATH"` |
| [app/Models/CommentMeta.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/CommentMeta.php#L5) | defined | `"ABSPATH"` |
| [app/Models/Post.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/Post.php#L5) | defined | `"ABSPATH"` |
| [app/Models/PostMeta.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/PostMeta.php#L5) | defined | `"ABSPATH"` |
| [app/Models/Reaction.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/Reaction.php#L5) | defined | `"ABSPATH"` |
| [app/Models/User.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/User.php#L5) | defined | `"ABSPATH"` |
| [app/Models/UserMeta.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Models/UserMeta.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/FrontendServiceProvider.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/FrontendServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/MenuServiceProvider.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/MenuServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/SettingsServiceProvider.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/Admin/SettingsServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Repositories/ReactionRepository.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Repositories/ReactionRepository.php#L5) | defined | `"ABSPATH"` |
| [app/Repositories/ReviewRepository.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Repositories/ReviewRepository.php#L5) | defined | `"ABSPATH"` |
| [config/app.php:3](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/config/app.php#L3) | defined | `'ABSPATH'` |
| [database/Migrations/CreateDB.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/CreateDB.php#L5) | defined | `"ABSPATH"` |
| [database/Migrations/CreateDB.php:21](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/CreateDB.php#L21) | function_exists | `'dbDelta'` |
| [database/Migrations/SnapMigration.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/SnapMigration.php#L5) | defined | `"ABSPATH"` |
| [database/Migrations/TestMigration.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/TestMigration.php#L5) | defined | `"ABSPATH"` |
| [database/Migrations/Snap/DirectoryBuilderSnap.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L5) | defined | `"ABSPATH"` |
| [database/Migrations/Snap/DirectoryBuilderSnap.php:311](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L311) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:3](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/enqueues/frontend-enqueue.php#L3) | defined | `'ABSPATH'` |
| [resources/views/create.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/create.php#L1) | defined | `'ABSPATH'` |
| [resources/views/delete-modal.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/delete-modal.php#L1) | defined | `'ABSPATH'` |
| [resources/views/filter.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/filter.php#L1) | defined | `'ABSPATH'` |
| [resources/views/header.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/header.php#L1) | defined | `'ABSPATH'` |
| [resources/views/login-alert.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/login-alert.php#L1) | defined | `'ABSPATH'` |
| [resources/views/pagination.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/pagination.php#L1) | defined | `'ABSPATH'` |
| [resources/views/report-modal.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/report-modal.php#L1) | defined | `'ABSPATH'` |
| [resources/views/review.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/review.php#L1) | defined | `'ABSPATH'` |
| [resources/views/reviews.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/reviews.php#L1) | defined | `'ABSPATH'` |
| [resources/views/upload.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/upload.php#L1) | defined | `'ABSPATH'` |
| [resources/views/admin/review-data.php:3](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/admin/review-data.php#L3) | defined | `'ABSPATH'` |
| [resources/views/admin/review-images.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/admin/review-images.php#L1) | defined | `'ABSPATH'` |
| [resources/views/components/action.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/components/action.php#L1) | defined | `'ABSPATH'` |
| [resources/views/components/listing-owner.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/components/listing-owner.php#L1) | defined | `'ABSPATH'` |
| [resources/views/components/reaction.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/components/reaction.php#L1) | defined | `'ABSPATH'` |
| [resources/views/components/reply-button.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/components/reply-button.php#L1) | defined | `'ABSPATH'` |
| [resources/views/components/stars.php:2](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/components/stars.php#L2) | defined | `'ABSPATH'` |
| [resources/views/mail/report.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/mail/report.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/create-form.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/create-form.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/index.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/index.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/create.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/create.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/edit.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/edit.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/footer.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/footer.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/index.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/index.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/sub-comment/edit.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/sub-comment/edit.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/sub-comment/footer.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/sub-comment/footer.php#L1) | defined | `'ABSPATH'` |
| [resources/views/replies/comment/sub-comment/index.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/resources/views/replies/comment/sub-comment/index.php#L1) | defined | `'ABSPATH'` |
| [routes/ajax/api.php:3](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/routes/ajax/api.php#L3) | defined | `"ABSPATH"` |
| [routes/rest/api.php:3](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/routes/rest/api.php#L3) | defined | `"ABSPATH"` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.

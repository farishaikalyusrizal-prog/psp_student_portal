<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <span class="badge text-bg-primary mb-2">Administration</span>
        <h1 class="h2 fw-bold mb-1">Student Grade Management</h1>
        <p class="text-secondary mb-0">Manage student profiles, programmes and assessment marks.</p>
    </div>
    <a class="btn btn-primary" href="<?= url('student-create') ?>"><i class="bi bi-plus-circle me-2"></i>Add Student</a>
</div>

<div class="card border-0 shadow-sm portal-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th class="ps-4">#</th>
                    <th>Name</th>
                    <th>IC / NRIC</th>
                    <th>Program</th>
                    <th>Marks</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!$students): ?>
                    <tr><td colspan="6" class="text-center py-5 text-secondary">No student records available.</td></tr>
                <?php else: ?>
                    <?php foreach ($students as $index => $row): ?>
                        <tr>
                            <td class="ps-4"><?= $index + 1 ?></td>
                            <td class="fw-semibold"><?= e($row['name']) ?></td>
                            <td><?= e($row['nric']) ?></td>
                            <td><span class="badge text-bg-light border"><?= e($row['program_code']) ?></span></td>
                            <td><span class="fw-semibold <?= (float)$row['marks'] >= 50 ? 'text-success' : 'text-danger' ?>"><?= e(number_format((float) $row['marks'], 2)) ?></span></td>
                            <td class="text-end pe-4 text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="<?= url('student-edit', ['id' => $row['id']]) ?>"><i class="bi bi-pencil-square"></i> Edit</a>
                                <form method="post" action="<?= url('student-delete') ?>" class="d-inline delete-form" data-name="<?= e($row['name']) ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>

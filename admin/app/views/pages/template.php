<?php
/**
 * TEMPLATE: Basic Page Structure
 * Copy this file and adapt for any new admin page.
 *
 * Usage:
 *   1. Create a Controller in app/controllers/YourController.php
 *   2. Add a Model in app/models/YourModel.php
 *   3. Create views in app/views/your_section/index.php
 *   4. Register routes in public/index.php
 */
?>

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-extrabold text-slate-800">Page Title</h2>
        <nav class="flex items-center gap-2 mt-1 text-xs text-slate-400">
            <a href="<?= APP_URL ?>/dashboard" class="hover:text-indigo-500 transition-colors" style="text-decoration:none">Dashboard</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-slate-600 font-medium">Page Title</span>
        </nav>
    </div>
    <div class="flex items-center gap-3">
        <!-- Action buttons go here -->
        <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:opacity-90"
                style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <i class="fas fa-plus text-xs"></i>
            Add New
        </button>
    </div>
</div>

<!-- Filter / Search Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-search"></i></span>
            <input type="text" placeholder="Search..."
                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <select class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Status</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
        <button class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
            <i class="fas fa-filter text-xs"></i> Filter
        </button>
    </div>
</div>

<!-- Main Data Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h3 class="font-bold text-slate-700 text-sm">Showing <span class="text-indigo-600">0</span> records</h3>
        <div class="flex items-center gap-2">
            <button class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs">
                <i class="fas fa-download"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded">
                    </th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Column 1</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Column 2</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50" id="table-body">
                <!-- Empty state -->
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-300 text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-slate-500 font-semibold text-sm">No records found</p>
                                <p class="text-slate-400 text-xs mt-1">Add your first entry to get started.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100">
        <p class="text-slate-400 text-xs">Showing 0 of 0 entries</p>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs disabled:opacity-40" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="w-8 h-8 rounded-lg bg-indigo-600 text-white text-xs font-bold">1</button>
            <button class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors flex items-center justify-center text-slate-500 text-xs disabled:opacity-40" disabled>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Inline actions row template (example row) -->
<!-- 
<tr class="hover:bg-slate-50/50 transition-colors">
    <td class="px-5 py-3.5"><input type="checkbox" class="rounded"></td>
    <td class="px-5 py-3.5"><span class="text-slate-800 text-sm font-semibold">Value</span></td>
    <td class="px-5 py-3.5"><span class="text-slate-500 text-sm">Value</span></td>
    <td class="px-5 py-3.5">
        <span class="badge bg-emerald-50 text-emerald-700">Active</span>
    </td>
    <td class="px-5 py-3.5"><span class="text-slate-400 text-xs">01 Jan 2025</span></td>
    <td class="px-5 py-3.5">
        <div class="flex items-center justify-end gap-2">
            <a href="#" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 flex items-center justify-center text-xs transition-colors">
                <i class="fas fa-pencil"></i>
            </a>
            <button onclick="confirmDelete(ID)" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center text-xs transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
-->

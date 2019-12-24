<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CREATE AND ATTACH PERMISSIONS
        $create_member = Permission::create(['name' => 'create member']);
        $read_member = Permission::create(['name' => 'read member']);
        $update_member = Permission::create(['name' => 'update member']);
        $disable_member = Permission::create(['name' => 'disable member']);
        $view_member_dashboard = Permission::create(['name' => 'view member dashboard']);
        $add_to_savings = Permission::create(['name' => 'add to savings']);
        $withdraw_from_savings = Permission::create(['name' => 'withdraw from savings']);
        $change_monthly_contribution = Permission::create(['name' => 'change monthly contribution']);
        $view_member_ledger = Permission::create(['name' => 'view member ledger']);

        $create_centre = Permission::create(['name' => 'create centre']);
        $read_centre = Permission::create(['name' => 'read centre']);
        $update_centre = Permission::create(['name' => 'update centre']);
        $delete_centre = Permission::create(['name' => 'delete centre']);

        $ltl_create = Permission::create(['name' => 'create long term loan']);
        $ltl_repay = Permission::create(['name' => 'long term loan repayment']);

        $stl_create = Permission::create(['name' => 'create short term loan']);
        $stl_repay = Permission::create(['name' => 'short term loan repayment']);

        $com_create = Permission::create(['name' => 'create commodity loan']);
        $com_repay = Permission::create(['name' => 'commodity loan repayment']);

        $generate_IPPIS_deduction_file = Permission::create(['name' => 'generate IPPIS deduction file']);
        $import_IPPIS_deduction_file = Permission::create(['name' => 'import and reconcile IPPIS deduction file']);

        $generate_reports = Permission::create(['name' => 'generate reports']);

        $manage_users = Permission::create(['name' => 'manage users']);



        // CREATE ROLES
        $member = Role::create(['name' => 'member']);
        $secretary = Role::create(['name' => 'secretary']);
        $ass_secretary = Role::create(['name' => 'ass secretary']);
        $treasurer = Role::create(['name' => 'treasurer']);
        $accountant = Role::create(['name' => 'accountant']);
        $fin_sec = Role::create(['name' => 'fin sec']);
        $ass_fin_sec = Role::create(['name' => 'ass fin sec']);
        $president = Role::create(['name' => 'president']);
        $coop_staff = Role::create(['name' => 'coop staff']);
        $auditor = Role::create(['name' => 'auditor']);


        // ASSIGN PERMISSIONS TO ROLES
        $member->givePermissionTo([$view_member_ledger]);

        $secretary->givePermissionTo([$create_member, $read_member, $update_member, $disable_member, $view_member_dashboard, $view_member_ledger, $create_centre, $read_centre, $update_centre, $delete_centre, $generate_reports]);

        $ass_secretary->givePermissionTo([$create_member, $read_member, $update_member, $disable_member, $view_member_dashboard, $view_member_ledger, $create_centre, $read_centre, $update_centre, $delete_centre, $generate_reports]);

        $treasurer->givePermissionTo([$read_member, $update_member, $view_member_dashboard, $view_member_ledger, $read_centre, $generate_IPPIS_deduction_file, $import_IPPIS_deduction_file, $generate_reports]);

        $accountant->givePermissionTo([$create_member, $read_member, $update_member, $view_member_dashboard, $add_to_savings, $withdraw_from_savings, $change_monthly_contribution, $view_member_ledger, $read_centre, $ltl_create, $ltl_repay, $stl_create, $stl_repay, $com_create, $com_repay, $generate_IPPIS_deduction_file, $import_IPPIS_deduction_file, $generate_reports]);

        $fin_sec->givePermissionTo([$read_member, $update_member, $view_member_dashboard, $add_to_savings, $withdraw_from_savings, $change_monthly_contribution, $view_member_ledger, $create_centre, $read_centre, $update_centre, $delete_centre, $ltl_create, $ltl_repay, $stl_create, $stl_repay, $com_create, $com_repay, $generate_IPPIS_deduction_file, $import_IPPIS_deduction_file, $generate_reports]);

        $ass_fin_sec->givePermissionTo([$read_member, $update_member, $view_member_dashboard, $add_to_savings, $withdraw_from_savings, $change_monthly_contribution, $view_member_ledger, $create_centre, $read_centre, $update_centre, $delete_centre, $ltl_create, $ltl_repay, $stl_create, $stl_repay, $com_create, $com_repay, $generate_IPPIS_deduction_file, $import_IPPIS_deduction_file, $generate_reports]);

        $president->givePermissionTo([$read_member, $update_member, $view_member_dashboard, $view_member_ledger, $read_centre, $generate_reports]);

        $coop_staff->givePermissionTo([$create_member, $read_member, $update_member, $view_member_dashboard, $add_to_savings, $withdraw_from_savings, $change_monthly_contribution, $view_member_ledger, $read_centre, $ltl_create, $ltl_repay, $stl_create, $stl_repay, $com_create, $com_repay, $generate_IPPIS_deduction_file, $import_IPPIS_deduction_file, $generate_reports]);

        $auditor->givePermissionTo([$read_member, $update_member, $view_member_dashboard, $view_member_ledger, $read_centre, $generate_reports]);

    }
}

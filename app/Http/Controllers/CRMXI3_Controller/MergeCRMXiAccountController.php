<?php

namespace App\Http\Controllers\CRMXI3_Controller;

use App\Http\Controllers\Controller;
use App\Models\CRMXI3_Model\CRMXIMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MergeCRMXiAccountController extends Controller
{
    /**
     * Merge the specified CRMXi Account.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchMergeAccount(Request $request)
    {
        // Validate the request
        $request->validate([
            'account_id' => ['required', 'string', 'max:50', 'exists:crmxi3_mains,account_id'],
        ]);

        // Query to find the account
        $account = CRMXIMain::query()
            ->where('account_id', $request->account_id)
            ->first();

        // If the account does not exist
        if (!$account) {
            return response()->json([
                'message' => 'Account not found'
            ], 404);
        }

        // If account is itself
        if ($request->current_account_id == $account->account_id) {
            return response()->json([
                'message' => 'Account cannot be merged with itself'
            ], 400);
        }

        // Return the account
        return response()->json([
            'data' => $account
        ], 200);
    }

    /**
     * Merge the specified account to current account
     *
     * @param Request $request
     */
    public function mergeAccounts(Request $request)
    {
        try {
            // Find the account to merge
            $crmxi = CRMXIMain::query()
                ->where('account_id', $request->account_id)
                ->firstOrFail();

            // Wrap to transaction
            DB::transaction(function () use ($request, $crmxi) {
                // Update the account_id attached to crmxi3_vehicles
                DB::table('crmxi3_vehicles')
                    ->where('account_id', $crmxi->account_id) // Account to merge
                    ->update(['account_id' => $request->merge_account_id]);  // Current account to merge to

                // Update the account_id attached to crmxi3_addresses
                DB::table('crmxi3_address')
                    ->where('account_id', $crmxi->account_id) // Account to merge
                    ->update(['account_id' => $request->merge_account_id]);  // Current account to merge to

                // Insert to crmxi3_mains_archive
                DB::table('crmxi3_mains_archive')
                    ->insert($crmxi->toArray());

                // Delete the account
                $crmxi->delete();
            });

            // Return success message
            return response()->json([
                'message' => 'Accounts merged successfully',
            ], 200);
        } catch (\Exception $e) {
            // Return error message
            return response()->json([
                'message' => 'An error occurred while merging the accounts'
            ], 500);
        }
    }
}

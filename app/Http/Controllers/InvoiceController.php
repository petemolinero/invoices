<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{
	/**
	 * Download an invoice PDF
	 * 
	 * @param     Request     $request     The HTTP request
	 * @return    void
	 */
	public function download(Request $request)
	{
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml(view('invoice'));

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();
		$dompdf->add_info('Producer', json_encode($request->all()));

		// Output the generated PDF to Browser
		$dompdf->stream();
	}
}

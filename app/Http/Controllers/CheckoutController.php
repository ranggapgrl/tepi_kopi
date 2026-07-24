use App\Services\RajaOngkirService;

class CheckoutController extends Controller
{
    public function __construct(protected RajaOngkirService $rajaOngkir) {}

    public function searchDestination(Request $request)
    {
        $request->validate(['q' => 'required|string|min:3']);

        $results = $this->rajaOngkir->searchDestination($request->q);

        return response()->json($results);
    }

    public function shippingCost(Request $request)
    {
        $request->validate([
            'destination_id' => 'required',
            'weight'         => 'required|integer|min:1', // gram
        ]);

        $result = $this->rajaOngkir->calculateCost(
            $request->destination_id,
            $request->weight,
            'jne:jnt:sicepat:pos:anteraja' // sesuaikan kurir yang mau ditampilkan
        );

        return response()->json($result);
    }
}
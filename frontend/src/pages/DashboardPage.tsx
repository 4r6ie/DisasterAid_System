import { useEffect, useMemo, useState } from 'react';
import {
  Area,
  AreaChart,
  Bar,
  BarChart,
  CartesianGrid,
  Cell,
  Legend,
  Pie,
  PieChart,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis,
} from 'recharts';
import { CircleMarker, MapContainer, Popup, TileLayer } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import { AlertTriangle, Building2, HandHelping, Siren } from 'lucide-react';
import {
  Card,
  CardBody,
  CardHeader,
  PageHeader,
  Spinner,
  StatCard,
} from '@/components/ui';
import { dashboardService } from '@/services/resources';
import { apiErrorMessage } from '@/services/http';
import { formatNumber, titleCase } from '@/utils/format';
import type { DashboardCharts, DashboardStats, MapPoint } from '@/types';

const PALETTE = ['#0284c7', '#f59e0b', '#dc2626', '#16a34a', '#7c3aed', '#0891b2', '#be185d'];

function toEntries(record: Record<string, number>): Array<{ name: string; value: number }> {
  return Object.entries(record).map(([name, value]) => ({ name: titleCase(name), value }));
}

function toTrend(
  record: Record<string, number>,
): Array<{ date: string; count: number }> {
  return Object.entries(record).map(([date, count]) => ({ date, count }));
}

function toOccupancyTrend(
  record: Record<string, { occupancy: number; capacity: number }>,
): Array<{ date: string; occupancy: number; capacity: number }> {
  return Object.entries(record).map(([date, value]) => ({ date, ...value }));
}

const severityColors: Record<string, string> = {
  low: '#16a34a',
  moderate: '#f59e0b',
  high: '#f97316',
  critical: '#dc2626',
  default: '#0284c7',
};

export function DashboardPage() {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [charts, setCharts] = useState<DashboardCharts | null>(null);
  const [points, setPoints] = useState<MapPoint[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function load() {
      setLoading(true);
      setError(null);

      try {
        const [statsData, chartsData, mapData] = await Promise.all([
          dashboardService.stats(),
          dashboardService.charts(),
          dashboardService.map(),
        ]);

        if (!active) return;

        setStats(statsData);
        setCharts(chartsData);
        setPoints(mapData);
      } catch (cause) {
        if (active) setError(apiErrorMessage(cause, 'Unable to load the dashboard.'));
      } finally {
        if (active) setLoading(false);
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, []);

  const typeData = useMemo(
    () => toEntries(charts?.disaster_by_type ?? {}),
    [charts],
  );
  const severityData = useMemo(
    () => toEntries(charts?.disaster_by_severity ?? {}),
    [charts],
  );
  const assistanceData = useMemo(
    () => toEntries(charts?.assistance_by_type ?? {}),
    [charts],
  );
  const trendData = useMemo(
    () => toTrend(charts?.disaster_trends ?? {}),
    [charts],
  );
  const occupancyData = useMemo(
    () => toOccupancyTrend(charts?.occupancy_trend ?? {}),
    [charts],
  );

  if (loading) {
    return (
      <div className="flex items-center justify-center py-20">
        <Spinner className="h-8 w-8" />
      </div>
    );
  }

  if (error) {
    return <p className="rounded-lg bg-danger-50 px-4 py-3 text-sm text-danger-700">{error}</p>;
  }

  const occupancyRate = stats && stats.evacuation.total_capacity > 0
    ? Math.round((stats.evacuation.current_occupancy / stats.evacuation.total_capacity) * 100)
    : 0;

  return (
    <div>
      <PageHeader
        title="Dashboard"
        description="Operational overview of disasters, response teams and relief inventory."
      />

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard
          label="Active Disasters"
          value={formatNumber(stats?.disasters.active ?? 0)}
          icon={<AlertTriangle className="h-5 w-5" />}
          tone="danger"
        />
        <StatCard
          label="Pending Emergencies"
          value={formatNumber(stats?.emergencies.pending ?? 0)}
          icon={<Siren className="h-5 w-5" />}
          tone="warning"
        />
        <StatCard
          label="Pending Assistance"
          value={formatNumber(stats?.assistance.pending ?? 0)}
          icon={<HandHelping className="h-5 w-5" />}
          tone="primary"
        />
        <StatCard
          label="Evacuation Occupancy"
          value={`${occupancyRate}%`}
          icon={<Building2 className="h-5 w-5" />}
          tone="success"
        />
      </div>

      <div className="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-3">
        <Card className="xl:col-span-2">
          <CardHeader
            title="Disaster Incidents"
            description="New incidents reported over the last 30 days"
          />
          <CardBody>
            <div className="h-72">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={trendData}>
                  <defs>
                    <linearGradient id="disasterGradient" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#0284c7" stopOpacity={0.3} />
                      <stop offset="95%" stopColor="#0284c7" stopOpacity={0} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                  <XAxis dataKey="date" tick={{ fontSize: 12 }} tickFormatter={(value: string) => value.slice(5)} />
                  <YAxis allowDecimals={false} tick={{ fontSize: 12 }} />
                  <Tooltip />
                  <Area
                    type="monotone"
                    dataKey="count"
                    stroke="#0284c7"
                    strokeWidth={2}
                    fill="url(#disasterGradient)"
                  />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </CardBody>
        </Card>

        <Card>
          <CardHeader title="Incidents by Severity" />
          <CardBody>
            <div className="h-72">
              <ResponsiveContainer width="100%" height="100%">
                <PieChart>
                  <Pie
                    data={severityData}
                    dataKey="value"
                    nameKey="name"
                    innerRadius={50}
                    outerRadius={85}
                    paddingAngle={2}
                  >
                    {severityData.map((entry) => (
                      <Cell
                        key={entry.name}
                        fill={severityColors[entry.name.toLowerCase()] ?? PALETTE[0]}
                      />
                    ))}
                  </Pie>
                  <Tooltip />
                  <Legend />
                </PieChart>
              </ResponsiveContainer>
            </div>
          </CardBody>
        </Card>
      </div>

      <div className="mt-6 grid grid-cols-1 gap-4 xl:grid-cols-3">
        <Card>
          <CardHeader title="Assistance by Type" />
          <CardBody>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={assistanceData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                  <XAxis dataKey="name" tick={{ fontSize: 11 }} />
                  <YAxis allowDecimals={false} tick={{ fontSize: 12 }} />
                  <Tooltip />
                  <Bar dataKey="value" fill="#0284c7" radius={[4, 4, 0, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </CardBody>
        </Card>

        <Card>
          <CardHeader title="Incidents by Type" />
          <CardBody>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <BarChart data={typeData} layout="vertical">
                  <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                  <XAxis type="number" allowDecimals={false} tick={{ fontSize: 12 }} />
                  <YAxis type="category" dataKey="name" width={90} tick={{ fontSize: 11 }} />
                  <Tooltip />
                  <Bar dataKey="value" fill="#16a34a" radius={[0, 4, 4, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </div>
          </CardBody>
        </Card>

        <Card>
          <CardHeader title="Evacuation Occupancy" description="Occupied vs. capacity over 30 days" />
          <CardBody>
            <div className="h-64">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={occupancyData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                  <XAxis dataKey="date" tick={{ fontSize: 11 }} tickFormatter={(value: string) => value.slice(5)} />
                  <YAxis tick={{ fontSize: 12 }} />
                  <Tooltip />
                  <Legend />
                  <Area type="monotone" dataKey="capacity" stroke="#94a3b8" fill="#e2e8f0" />
                  <Area type="monotone" dataKey="occupancy" stroke="#7c3aed" fill="#ddd6fe" />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </CardBody>
        </Card>
      </div>

      <Card className="mt-6">
        <CardHeader
          title="Incident Map"
          description="Disaster incidents, emergency reports and evacuation centers"
        />
        <CardBody>
          <div className="h-96 overflow-hidden rounded-lg">
            <MapContainer
              center={[14.5995, 120.9842]}
              zoom={6}
              style={{ height: '100%', width: '100%' }}
            >
              <TileLayer
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
              />

              {points.map((point) => {
                const color =
                  point.type === 'disaster'
                    ? severityColors[point.severity ?? ''] ?? severityColors.default
                    : point.type === 'emergency'
                      ? severityColors[point.priority ?? ''] ?? severityColors.default
                      : '#0284c7';

                return (
                  <CircleMarker
                    key={`${point.type}-${point.id}`}
                    center={[point.lat, point.lng]}
                    radius={point.type === 'disaster' ? 8 : 6}
                    pathOptions={{ color, fillColor: color, fillOpacity: 0.6 }}
                  >
                    <Popup>
                      <div className="text-sm">
                        <p className="font-semibold">{point.title}</p>
                        <p className="capitalize text-slate-600">
                          {point.type.replace('_', ' ')} · {point.subtype ?? point.status}
                        </p>
                        {point.count !== undefined && (
                          <p className="text-slate-600">Affected: {formatNumber(point.count)}</p>
                        )}
                        {point.occupancy !== undefined && point.capacity !== undefined && (
                          <p className="text-slate-600">
                            Occupancy: {formatNumber(point.occupancy)} / {formatNumber(point.capacity)}
                          </p>
                        )}
                      </div>
                    </Popup>
                  </CircleMarker>
                );
              })}
            </MapContainer>
          </div>
        </CardBody>
      </Card>
    </div>
  );
}

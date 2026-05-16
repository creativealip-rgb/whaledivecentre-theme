<?php
/**
 * Template Name: Dashboard
 */
require_once get_template_directory() . '/dashboard-header.php';

$user_id = get_current_user_id();
$course_requests = get_user_meta($user_id, '_wdc_course_requests', true);
$gear_requests = get_user_meta($user_id, '_wdc_gear_requests', true);
$course_requests = is_array($course_requests) ? $course_requests : [];
$gear_requests = is_array($gear_requests) ? $gear_requests : [];
?>
<div style="margin-bottom:24px;">
    <h1 style="font-size:28px;font-weight:800;color:#0f172a;margin-bottom:8px;">Member Dashboard</h1>
    <p style="font-size:15px;color:#64748b;">Your Whale Dive Centre hub for course planning, scuba gear requests, and crew support.</p>
</div>

<div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px;margin-bottom:28px;">
    <div style="background:linear-gradient(135deg,#eef9fc,#dff4fa);padding:20px;border-radius:16px;border:1px solid #ccecf5;">
        <div style="font-size:12px;color:#0b617c;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;">Course Requests</div>
        <div style="font-size:34px;font-weight:950;color:#06384d;"><?php echo count($course_requests); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#fff7ed,#ffedd5);padding:20px;border-radius:16px;border:1px solid #fed7aa;">
        <div style="font-size:12px;color:#9a3412;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;">Gear Requests</div>
        <div style="font-size:34px;font-weight:950;color:#7c2d12;"><?php echo count($gear_requests); ?></div>
    </div>
    <div style="background:linear-gradient(135deg,#f8fafc,#eef2ff);padding:20px;border-radius:16px;border:1px solid #e2e8f0;">
        <div style="font-size:12px;color:#475569;text-transform:uppercase;letter-spacing:.08em;font-weight:900;margin-bottom:8px;">Crew Status</div>
        <div style="font-size:22px;font-weight:950;color:#0f172a;">Ready to help</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;margin-bottom:28px;">
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <span style="font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;">Learn</span>
        <h2 style="font-size:24px;color:#0f172a;margin:10px 0 10px;">Join a dive course</h2>
        <p style="color:#64748b;line-height:1.65;margin:0 0 18px;">Start Open Water, continue to Advanced, or build safer rescue and leadership skills with the crew.</p>
        <a href="/my-courses/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;">Open My Courses</a>
    </article>
    <article style="background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:24px;box-shadow:0 12px 34px rgba(15,23,42,.06);">
        <span style="font-size:11px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;">Gear</span>
        <h2 style="font-size:24px;color:#0f172a;margin:10px 0 10px;">Buy scuba equipment</h2>
        <p style="color:#64748b;line-height:1.65;margin:0 0 18px;">Browse masks, fins, BCDs, regulators, wetsuits, and dive computers with fit support before checkout.</p>
        <a href="/my-gear/" style="display:inline-flex;padding:11px 16px;border-radius:999px;background:#06384d;color:#fff;text-decoration:none;font-weight:900;">Open My Gear</a>
    </article>
</div>

<div style="background:linear-gradient(135deg,#f8fdff,#eef9fc);border:1px solid #ccecf5;border-radius:20px;padding:22px;">
    <div style="font-size:12px;font-weight:950;text-transform:uppercase;letter-spacing:.1em;color:#0b617c;margin-bottom:6px;">Recommended next step</div>
    <h2 style="font-size:22px;color:#06384d;margin:0 0 6px;">Pick a course or request gear advice.</h2>
    <p style="color:#64748b;margin:0;line-height:1.6;">The member area now focuses on what Whale Dive Centre members need most: joining courses and buying the right dive gear.</p>
</div>
<?php require_once get_template_directory() . '/dashboard-footer.php'; ?>

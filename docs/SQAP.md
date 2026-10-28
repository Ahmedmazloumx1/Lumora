# Lumora software quality assurance plan

This plan covers the existing PHP storefront and its customer, catalog, cart, cash-on-delivery order, custom-design and administration flows. Online payment, password-reset email and transactional email are not implemented and are not acceptance requirements for the current student release.

## Team and ownership

Ahmed Mazloum is one person; the other available members are Abdulrahman Maher and Nermeen. Sara has not joined Jira. Use verified personal accounts for authorship and independent review; do not create fictitious contributions. Estimates are provisional until team consensus, as required by the phase-one document.

## Standards and completion

Use PHP 8.3, prepared PDO statements, escaped HTML output, server-side validation, CSRF checks and role/record ownership checks. Do not commit credentials, session files, real customer data or database exports. JavaScript must parse; PHP must pass syntax and PHPStan level 0. Raise the analysis level through reviewed remediation rather than silently ignoring new failures.

To Do → In Progress → In Review → QA Gate → Done is the intended development workflow. QA Gate requires a passing CI run on the PR revision, independent peer review, linked test results and triaged defects. Jira configuration and GitHub enforcement must be verified separately; this document does not prove they are enabled.

## Branch and review policy

Main is the release branch; develop is optional and should only be introduced if the team needs an integration branch. Use feature/LMR-XX-description, bugfix/LMR-XX-description or hotfix/LMR-XX-description with an existing Jira key. PR titles begin LMR-XX:. Commit convention: LMR-XX: imperative summary. The actual project key is LMR, not SCRUM.

Main now requires one independent approval, approval of the latest push, dismissal of stale approvals, the passing PHP JavaScript SQL and security check, an up-to-date branch and resolved review conversations. Administrators cannot bypass these rules; force pushes and deletions are disabled. GitHub Free confirms enforcement for this currently public repository. Verify these settings again if its visibility or plan changes. PR #1 is open and cannot merge until genuine peer review.

GitHub for Atlassian is installed for Ahmedmazloumx1/Lumora only, with owner approval. LMR-41's native Development panel shows its feature branch, commit/build information and open PR #1. Optional Rovo indexing was left disabled.

## Tests and defects

Test Cases use Active, Pass and Fail; unexecuted or blocked cases stay Active with Actual Result = Not Executed. Include Story/AC links, preconditions, dummy data, steps, expected/actual results, tester, commit/environment and safe evidence. A local smoke-suite pass does not automatically pass every Jira case.

Bugs use New → Open → Fixed → Closed; failed retest returns Fixed → Open. Fixed needs a real fix commit; Closed needs recorded retest. Severity is distinct from priority: Critical (security/data loss/core outage), Major (core flow fails), Minor (limited impact/workaround), Trivial (cosmetic).

Track Technical Debt and Quality Task as separate non-development work types. Record root cause as application logic, validation, authorization/session, data/concurrency, configuration, process or undetermined. Use five whys only when evidence supports the conclusions; otherwise keep hypotheses explicit.

## Four-phase evidence plan

1. Planning: concise verified requirements, supported hierarchy, team estimates, workflow/field walkthrough and repository policy. The owner's no-development-Tasks instruction takes precedence over the phase-one document's Task breakdown.
2. Gates: PHP/JavaScript syntax, PHPStan, dependency audit and isolated SQL/HTTP smoke suite in GitHub Actions. SonarCloud/SonarQube remains pending actual organization/project credentials. Retain PR run/review links.
3. Audit: defect lifecycle, evidence-based RCA, PR compliance review and metrics with explicit denominators and observation periods. Do not invent Sonar ratings, DRE, escaped defects or resolution time. Unknown measurements remain unavailable.
4. Presentation: 15-minute presentation and 10-minute live demo/discussion, using actual Jira/GitHub evidence and personal contributions.

## Five genuine PR candidates

These are planned improvements, not completed reviews or merges:

| Jira | Real improvement | Verification |
| --- | --- | --- |
| LMR-41 | SQA workflow, templates and plan | CI run plus independent review |
| LMR-50 | Atomic quantity-limited cart merge | 600+600 overflow and retry regression |
| LMR-51 | Persistent login rate limiting | Fresh-session cooldown regression |
| LMR-52 | Revocation of existing sessions | Deleted/suspended-user access regression |
| LMR-53 | Required shipping profile validation | Empty/whitespace fields preserve saved data |

Each requires its own genuine implementation, personal reviewer, passing checks and merge history. Do not create empty branches or manufacture five approvals/merges.

## Verified local evidence on 28 October 2026

The existing isolated smoke suite passed 195 checks and removed its disposable database. The static scanner passed 67 PHP files and two JavaScript modules; PHPStan 2.3.3 at level 0 reported no errors. These are local results, not GitHub Actions results or proof that all recorded defects are fixed. Inline JavaScript rendered inside PHP templates is outside the standalone JavaScript syntax scan. Credential-pattern checks are limited and do not constitute a full vulnerability assessment.

The initial hosted SQA Gate passed in run 40682129938 for commit 6c54d51665fe0c61ae23e71c33dba20d8f88a23c. Later revisions must pass their own run. Gate logs are retained for 14 days as workflow artifacts; explicit Bash pipefail behavior preserves failures when output is captured with tee.

## Jira consolidation and execution policy

LMR has five Epics and 18 development Stories. Ten active Feature groupings are retained alongside seven deferred/reference Feature records to preserve meaningful earlier work. Native Feature-to-Story parenting is unavailable on this Jira plan; both are direct Epic children, with explicitly described Feature associations. This limitation remains open and is not represented as a native three-level hierarchy.

The 18 Story estimates total 90 provisional points: Ahmed Mazloum 29, Abdulrahman Maher 30, Nermeen 31. These include historical baseline scope and are not sprint velocity. Existing 14–28 October sprint dates are preserved; the next proposed two-week sprints cover hardening and release/QA. Sara awaits an actual Jira account. Each Story names a different proposed verifier.

The release suite reuses 36 Test Cases (two per Story) covering all Story criteria, with independent testers. All remain Active / Not Executed. The original 119 Test Cases are retained for historical and extended coverage; 83 are outside the compact release view. No test result was inferred from a Story's Done state. Four previously reproduced defects remain Open with pending fixes and retests. Quality Task and Technical Debt use separate work types; no development Task or Subtask issues are used.

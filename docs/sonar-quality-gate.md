# SonarQube Cloud quality gate

1. Bind the Lumora repository in SonarQube Cloud and select GitHub Actions as the analysis method. Turn off Sonar automatic analysis for this project while CI analysis is enabled.
2. In GitHub Actions repository settings, set the variables `SONAR_ORGANIZATION` and `SONAR_PROJECT_KEY` to the published project values. Add `SONAR_TOKEN` as an Actions secret with permission to execute analysis. The workflow uses the EU SonarQube Cloud service at `https://sonarcloud.io`.
3. Run a scan on the review branch and confirm the quality gate appears for the exact pull request revision. After merging, verify the `main` baseline and give course reviewers access to the dashboard.
4. Add the `SonarQube Cloud quality gate` job as a required check on `main` once the first scan works. Confirm a failed gate prevents merging and a passing gate permits the normal reviewed merge.

The scan runs for pull requests from this repository and pushes to `main`. Pull requests from forks cannot use the analysis secret. To apply the same gate to a fork contribution, a maintainer first reviews the changes and puts them on a branch in this repository. Keep Sonar credentials in GitHub Actions secrets.

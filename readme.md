# IssueToTask

Connecting and syncing Github's issues with Teamwork's task lists.

## Warning

This requires the `rossedman/teamwork` library to be edited as shown in [this pull request](https://github.com/rossedman/teamwork/pull/3).

## Setup

1.  Edit `.env` and add GH_TOKEN=<your-gh-token>
  * _Make sure the token has the `repo`, `user:email`, and `admin:repo_hook` permissions available
1.  Edit `.env` and add GH_REPO=<your-org>/<your-repo>


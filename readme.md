# IssueToTask

Connecting and syncing Github's issues with Teamwork's task lists.

## Warning

This requires the `rossedman/teamwork` library to be edited as shown in [this pull request](https://github.com/rossedman/teamwork/pull/3).

## Setup

1.  Edit `.env` and add the following:
  * `GH_TOKEN=<your-gh-token>`
    * _Make sure the token has the `repo`, `user:email`, and `admin:repo_hook` permissions available
  * `GH_REPO=<your-org>/<your-repo>`
  * `TW_TOKEN=<your-teamwork-token>`
  * `TW_URL=<your-teamwork-url>`
  * `TW_PROJECT_ID=<your-teamwork-project-id>`
  * `TW_PERSON_ID=<your-teamwork-person-id>` This is the person that will be assigned to each milestone

## Github -> Teamwork Sync
*Here's how it works currently*

### Installing Webhook
**Not Implemented**

### Displaying Sync Status

1.  Check Webhook installed
  1.  Grabs all hooks installed for `<organization>/<repo>`
  1.  Grabs the base url of the dashboard
  1.  Iterates through each webhook looking for a substring match with `$hook['config']['url']`
  1.  Returns all relevant webhooks
  1.  Displays false if the number of webhooks is zero
1.  Check Milestones Synced
  1.  Grabs all milestones from Github and Teamwork
  1.  Iterates through each Github Milestone
    *  Tries to match each Github milestone to the Teamwork milestone based on title
      *  If the milestone is matched, checks tasklist based on title.  If matched,
        * Checks that the tasklist is attached to the milestone
        * Compares task count vs. Github milestone open issue count
  1.  Returns array of sync statuses for each milestone

### Syncing a milestone

*  POST route receives a Github milestone `number`
1.  Grabs Github milestone for `<organization>/<repo>` by `number`
1.  Grabs all Teamwork milestones
1.  Checks if the Teamwork milestone already exists and returns message if matched
  * If matched,
    1.  Creates Teamwork milestone, copying `title`, `description`, `deadline`, and setting `notify=false`, `reminder=false`, and `responsible-party-ids=<your-teamwork-person-id>`
      * If the Github milestone due date doesn't exist, it's currently setting it as the [Linux Epoch Time](https://en.wikipedia.org/wiki/Unix_time)
    1.  Assumes the tasklist doesn't exist and creates it, copying `name`, `description`, and setting `private=false`, `pinned=true`, and `milestone-id=<new-milestone-id>`
      * This also attaches the tasklist to the milestone through the `milestone-id` argument
    1.  Assumes the tasks don't exist and creates them, by copying `content`, `description`, and setting `notify=false`, `tags='Github Import'`
      * These tasks are created under the tasklist above
1. When finished syncing, Angular makes a call back to the [Displaying Sync Status](#displaying-sync-status) API route mentioned above to update the dashboard

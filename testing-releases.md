# Steps for Testing Releases

## Authentication
* Users should be able to log in
* Users should be able to log out
* Users should be able to download a "Backup"


## People
On the landing page there should be no issues. 
* Clicking on a letter to filter on that letter of the alphabet should result in a list with last names starting only with that letter.
* Clicking on "Filter Results" should not result in an error
* Filters should produce expected results on the resulting form
  * Example: Showing only veterans should result in a list where every record should have a "Yes" in the Is Vet column. 
### Adding and editing individuals
* Add a new person should not result in errors
* Editing the person should also not result in errors.
* Adding and editing phones should not result in errors and should behave predictably.
* Adding, editing, and deleting an address should behave as predicted. 
* Add a person to the household. 
* Deleting an address will either completely remove it from the database if it is only associated with the selected individual, or should just deledte the address association if more than one person is at that address.
* Should be able to export a functional address vcf file. 
* Should be able to view all the tours a person has been on without error
* Should be able to add a person to an upcoming tour.
* Should be able to add a person to a tour that has already been run. 
* Should be able to delete someone completely who has not been on a tour
* Should not be able to completely delete someone who has been on a tour, only make their record inactive. 

## Tours
* Tour list should appear by start date and without error. 

### Add a tour
* Should be able to add a tour without error
* Should be able to add a letter for the tour. 
* Should be able to resize message window
* Should be able to save without error.
* Should be able to re-edit without error.
* Should be able to delete.

#### Adding tourists and payers. 
* Find a person in the find people field 
* Click on Join Tour
* Should show a list of available tours
* Add as payer should show a page with payment options
* Add as tourist should result in a pop-up dialog 



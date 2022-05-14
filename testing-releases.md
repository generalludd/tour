# Steps for Testing Releases

## Update your environment
* Open the project in your IDE (VS Code, PhpStorm)
* Open a terminal window (In VS Code there's a Terminal menu)
* type `pwd` in the terminal window. 
  * type `ls -la` to see what is in the current directory
  * if there is a directory called `src` then type `cd src`
  * if there is a `system` and an `application` directory in the list you are already in the right place.
* Pull the latest code by typing `git pull`

## Start up Docker
* To start up the container in docker do the following.
* type `cd ..` in the terminal to move up one directory
* type `docker-compose up -d` to launch docker
* wait about 3 minutes and then go to `tours.test` to see if the site has loaded properly.

## Authentication & Account
* Users should be able to log in http://tours.test/auth

### Once Logged in...
* User should be able to change their password while logged in by clicking on "Account Info" in the upper right.
* Users should be able to log out (top right of screen)
* Users should be able to add a new user (though this is rarely done)

## Utility

![utility_buttons](https://user-images.githubusercontent.com/571478/168403799-2e36786a-4337-4499-bdca-b85a0c76c9ef.png)
* Users should be able to download a "Backup"

## Main Navigation
There are 3 buttons and one search field in the main navigation. 
![image](https://user-images.githubusercontent.com/571478/168404159-a5a40d19-708e-4f22-b190-1e1e3f195f8c.png)
"Home" and "People" go to the list of people.

## People
* Click on "Home" or "People" to view the addressbook of people. 
* There should be no errors on the landing page. 
* There should be a list of people. 
* In the "Find People" field typing the start of a name like "Julian". You should see a list of people and possible actions (show and join tour). 

### Filtering
* Clicking on a letter to filter on that letter of the alphabet should result in a list with last names starting only with that letter.
* Clicking on "Filter Results" should not result in an error 
* ![image](https://user-images.githubusercontent.com/571478/168404077-d069a17b-d18f-4a4b-a124-df631bef2176.png)
* Filters should produce expected results on the resulting form
  * Example: Showing only veterans should result in a list where every record should have a "Yes" in the Is Vet column. 

### Exporting
* Exporting records should result in a usable spreadsheet (no columns out of place, same records as shown on the filtered results). 


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
* Should show a list of available tours that the person has not been on yet. 
* Add as payer should show a page with payment options
* Add as tourist should result in a pop-up dialog with existing payers (or nothing if there are no payers).  
* When clicking on existing payers, it should show the tour list with the person's name added under the existing payer
* When clicking on Tour History, it should not show an error and should show an accurate list of tours the person has been on past and present. 

#### Adding Payments
* Go to the tour page and edit a payment
* Add a payment that is almost the amount of the total
* Go back to the tour list
* Check the subtotals and amount due for the person you edited.
* Test out reimbursements and changes to room selection sizes to be sure that the total due is correctly calculated

#### Creating Welcome Letters
* Go to the tour page
* Click on "Letter Templates"
* Add a new letter template with a name such as "Welcome Letter"
* Go back to the tour page and click on "Send Letter" next to one of the participants
* You should see a page with the content of the template.
* You should be able to edit the template from this page.
* You should be able to click on the date and the name fields to change the date and salutation for this letter.
* You should be able to add an additional note.
* The bottom of the letter should show the details of the person's tour including payments, amount due, tourists on the ticket, and other details. 
* If you print preview, it should show a letter with proper margins. 



# Hotels
* Go to a tour and click "Hotels"
* Click "Add Hotel"
* Add a hotel and enter 1 as the stay and click save.
* Click "Roomates"
* Click "Add Room"
* Click on "Double" and change to "Single"
* Tab out of the field
* Click on "Add Roommate"
* You should have a list of available roommates.
* Click "Add Room"
* click "Add Roommate"
* The list should not include the person in the first room.
* Refresh the page to be sure the roommate list and room sizes stick. 
* Click "Add Stay (Hotel)"
* Create a new hotel.
* Click "Roommates"
* Click "Duplicate from Previous stay
* Choose print: the output should be reasonably formatted without any buttons or errors. 



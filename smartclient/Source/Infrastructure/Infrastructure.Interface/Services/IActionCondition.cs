//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by this guidance package as part of the solution template
//
// The IActionCondition interface is used along with the IActionCatalogService
// to provide the conditional execution of an action. By implementing this interface you 
// will be able to register it in the ActionCatalogService.
//
// For more information see: 
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/03-01-140-How_to_Use_the_Action_Catalog.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using Microsoft.Practices.CompositeUI;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.Services
{
    public interface IActionCondition
    {
        bool CanExecute(string action, WorkItem context, object caller, object target);
    }
}
